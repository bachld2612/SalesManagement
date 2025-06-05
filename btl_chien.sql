--Proc: Lấy số lượng yêu thích của một sản phẩm bất kì

create PROCEDURE sp_GetFavoriteCount
    @product_id INT
AS
BEGIN
    SELECT favorite_count
    FROM Products
    WHERE id = @product_id;
END;


GO 

--Lấy ra thông tin của tất cả sản phẩm mà 1 khách hàng bất kì đã mua
ALTER PROCEDURE sp_GetUserPurchasedProducts
    @user_id INT
AS
BEGIN
    SELECT Products.id, Products.name, Products.price, Products.image_link, Order_Details.amount
    FROM Order_Details
    JOIN Products ON Order_Details.product_id = Products.id
    JOIN Orders  ON Orders.id = Order_Details.order_id
    WHERE Orders.user_id = @user_id
    AND Orders.state = 2 -- Chỉ lấy đơn hàng đã hoàn tất
END;





EXEC sp_GetFavoriteCount 24


--Trigger sau khi xóa khỏi favourite_lists
CREATE TRIGGER trg_AfterDeleteFavouritelist_UpdateFavoriteCount
ON Favourite_lists
AFTER DELETE
AS
BEGIN
    UPDATE Products
    SET favorite_count = favorite_count - 1
    WHERE id IN (SELECT product_id FROM DELETED);
END;
GO

select * from products


--Trigger sau khi thêm vào favourite_lists
CREATE TRIGGER trg_AfterInsertFavouritelists_UpdateFavoriteCount
ON Favourite_lists
AFTER INSERT
AS
BEGIN
    UPDATE Products
    SET favorite_count = favorite_count + 1
    WHERE id IN (SELECT product_id FROM INSERTED);
END;
GO

drop trigger trg_AfterInsertFavouritelists_UpdateFavoriteCount


--trigger kiểm tra số sao nhập vào của rates
CREATE TRIGGER trg_CheckStarValue
ON Rates
FOR INSERT, UPDATE
AS
BEGIN
    IF EXISTS (SELECT * FROM inserted WHERE star < 1 OR star > 5)
    BEGIN
        RAISERROR ('Giá trị sao phải nằm trong khoảng từ 1 đến 5.', 16, 1);
        ROLLBACK TRANSACTION;
    END
END;
GO
--Cập nhật trạng thái đơn hàng khi thanh toán: Trigger tự động 
--cập nhật trạng thái state của bảng Orders thành 1 
--(hoàn thành) khi có thanh toán.(payments)
ALTER TRIGGER trg_UpdateOrderState
ON Payments
AFTER INSERT
AS
BEGIN
    UPDATE Orders
    SET state = 2
    WHERE id IN (SELECT order_id FROM inserted);
END;
GO


--function LẤY RA TẤT CẢ SẢN PHẨM YÊU THÍCH CỦA 1 KH bất kì
 
ALTER FUNCTION fn_GetUserFavouriteProducts
(
    @UserId INT
)
RETURNS TABLE
AS
RETURN
(
    SELECT 
		Products.id,
        Products.name,
        Products.price,
        Products.image_link
    FROM 
        Favourite_Lists
    JOIN 
        Products ON Favourite_Lists.product_id = Products.id
    WHERE 
        Favourite_Lists.user_id = @UserId
);
GO
select * from dbo.fn_GetUserFavouriteProducts (30);


--lấy ra số sao trung bình của 1 sản phẩm bất kì
CREATE FUNCTION fn_AvgRating (@product_id INT)
RETURNS DECIMAL(3, 2)
AS
BEGIN
    DECLARE @avg DECIMAL(3, 2);
    SELECT @avg = AVG(CAST(star AS DECIMAL(3, 2)))
    FROM Rates
    WHERE product_id = @product_id;
    RETURN @avg;
END;
GO

--Lấy thông tin đánh giá của all sản phẩm
ALTER FUNCTION fn_CalculateAverageRating()
RETURNS TABLE
AS
RETURN
(
    -- Tạo bảng tạm để lưu thông tin đánh giá
    SELECT 
        Products.id AS product_id,
        Products.name AS product_name,
        AVG(CAST(Rates.star AS FLOAT)) AS average_rating,
        COUNT(Rates.star) AS total_ratings
    FROM 
        Products
    LEFT JOIN 
        Rates ON Products.id = Rates.product_id
    GROUP BY 
        Products.id, Products.name
);

SELECT * 
FROM dbo.fn_CalculateAverageRating();




--View hiển thị ra top những sản phẩm có số sao trung bình cao nhất (>=4)
ALTER VIEW vw_ProductRatings AS
SELECT TOP 9
    Products.id AS product_id,
    Products.name AS product_name,
	Products.image_link,
    ISNULL(AVG(CAST(Rates.star AS FLOAT)), 0) AS average_rating
FROM Products
LEFT JOIN Rates ON Products.id = Rates.product_id
GROUP BY Products.id, Products.name,Products.image_link
HAVING AVG(CAST(Rates.star AS FLOAT)) >= 4
ORDER BY average_rating DESC;

select * from vw_ProductRatings


--view hiển thị ra top những sản phẩm có số lượng yêu thích cao nhất

ALTER VIEW vw_ProductFavoriteDetails AS
SELECT TOP 9
    Products.id ,
    Products.name, 
    Products.price,
	Products.image_link,
    Products.favorite_count 
FROM Products
WHERE favorite_count >0
ORDER BY Products.favorite_count DESC;

select * from users

--------Hiển thị danh sách yêu thích với thông tin sp và khách hàng
ALTER VIEW vw_FavouriteDetails AS
SELECT 
    favourite_lists.id,
    Users.fullname,
    Products.name,
    Products.price
FROM favourite_lists
JOIN Users ON favourite_lists.user_id = Users.id
JOIN Products ON favourite_lists.product_id = Products.id;
GO

select * from vw_FavouriteDetails

select dbo.fn_avgRating (29)

--hiển thị thông tin thanh toán kèm đơn hàng
CREATE VIEW vw_PaymentDetails
AS
SELECT 
    p.id AS PaymentID,
    o.id AS OrderID,
    o.order_price,
    p.payment_date,
    p.payment_method
FROM Payments p
JOIN Orders o ON p.order_id = o.id;
GO

--cursor

DECLARE payment_cursor CURSOR FOR
SELECT order_id, payment_method, payment_date
FROM Payments;

OPEN payment_cursor;

DECLARE @order_id INT, @payment_method NVARCHAR(50), @payment_date DATETIME;
FETCH NEXT FROM payment_cursor INTO @order_id, @payment_method, @payment_date;

WHILE @@FETCH_STATUS = 0
BEGIN
    PRINT 'Đơn hàng ID: ' + CAST(@order_id AS NVARCHAR) + ', Phương thức: ' + @payment_method + ', Ngày: ' + CAST(@payment_date AS NVARCHAR);
    FETCH NEXT FROM payment_cursor INTO @order_id, @payment_method, @payment_date;
END;

CLOSE payment_cursor;
DEALLOCATE payment_cursor;



---
DECLARE @product_id INT;
DECLARE @product_name NVARCHAR(100);
DECLARE @average_rating FLOAT;
DECLARE @total_favourites INT;
DECLARE @total_payments INT;

-- Cursor lấy ra thông tin của sản phẩm (avgRate,favouriteCount,..)
DECLARE ProductCursor CURSOR FOR
SELECT 
    p.id, 
    p.name
FROM Products p;

OPEN ProductCursor;

FETCH NEXT FROM ProductCursor INTO @product_id, @product_name;

WHILE @@FETCH_STATUS = 0
BEGIN
    PRINT '-------------------------------';
    PRINT 'Product ID: ' + CAST(@product_id AS NVARCHAR(10));
    PRINT 'Product Name: ' + @product_name;

    -- Fetch average rating for the product
    SELECT @average_rating = ISNULL(AVG(CAST(r.star AS FLOAT)), 0)
    FROM Rates r
    WHERE r.product_id = @product_id;
    PRINT 'Average Rating: ' + CAST(@average_rating AS NVARCHAR(10));

    -- Fetch total number of times the product is in favourite lists
    SELECT @total_favourites = COUNT(*)
    FROM Favourite_Lists f
    WHERE f.product_id = @product_id;
    PRINT 'Total Favourites: ' + CAST(@total_favourites AS NVARCHAR(10));

    -- Fetch total payments related to this product
    SELECT @total_payments = COUNT(*)
    FROM Payments pmt
    JOIN Orders o ON pmt.order_id = o.id
    JOIN Order_Details od ON o.id = od.order_id
    WHERE od.product_id = @product_id;
    PRINT 'Total Payments: ' + CAST(@total_payments AS NVARCHAR(10));

    PRINT '-------------------------------';

    FETCH NEXT FROM ProductCursor INTO @product_id, @product_name;
END;

CLOSE ProductCursor;
DEALLOCATE ProductCursor;



--Cursor duyệt qua từng người dùng (Users) và lấy thông tin chi tiết về các sản phẩm họ đã đánh giá, cùng với số sao đã cho.

DECLARE @username NVARCHAR(50);
DECLARE @product_name NVARCHAR(100);
DECLARE @star INT;
DECLARE @description NVARCHAR(MAX);

-- Cursor to iterate over users
DECLARE UserCursor CURSOR FOR
SELECT username FROM Users;

OPEN UserCursor;

FETCH NEXT FROM UserCursor INTO @username;

WHILE @@FETCH_STATUS = 0
BEGIN
    PRINT '----------------------------------';
    PRINT 'Username: ' + @username + CHAR(13) + CHAR(10);

    -- Inner cursor to fetch products rated by the user
    DECLARE ProductRatingCursor CURSOR FOR
    SELECT 
        p.name AS product_name, 
        r.star, 
        r.description
    FROM Rates r
    JOIN Products p ON r.product_id = p.id
    JOIN Users u ON r.user_id = u.id
    WHERE u.username = @username;

    OPEN ProductRatingCursor;

    FETCH NEXT FROM ProductRatingCursor INTO @product_name, @star, @description;

    WHILE @@FETCH_STATUS = 0
    BEGIN
        PRINT '  Product Name: ' + @product_name + CHAR(13) + CHAR(10) +
              '  Star: ' + CAST(@star AS NVARCHAR(10)) + CHAR(13) + CHAR(10) +
              '  Comment: ' + ISNULL(@description, 'No comment') + CHAR(13) + CHAR(10);
        FETCH NEXT FROM ProductRatingCursor INTO @product_name, @star, @description;
    END;

    CLOSE ProductRatingCursor;
    DEALLOCATE ProductRatingCursor;

    FETCH NEXT FROM UserCursor INTO @username;
END;

CLOSE UserCursor;
DEALLOCATE UserCursor;
--het