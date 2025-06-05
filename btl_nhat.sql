-- Create the database
CREATE DATABASE SalesManagement;
GO
use SalesManagement


-- Table: Roles
CREATE TABLE Roles (
    name NVARCHAR(50) PRIMARY KEY, -- Role name as the ID
    description NVARCHAR(255) NULL
);
GO

-- Table: Users
CREATE TABLE Users (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username NVARCHAR(50) NOT NULL UNIQUE,
    password NVARCHAR(255) NOT NULL,
    fullname NVARCHAR(100),
    address NVARCHAR(MAX),
    role_name NVARCHAR(50) NOT NULL, -- Reference to Roles table
    phone_number NVARCHAR(15),
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (role_name) REFERENCES Roles(name) ON DELETE CASCADE
);
GO

-- Table: Suppliers
CREATE TABLE Suppliers (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name NVARCHAR(100) NOT NULL,
    email NVARCHAR(100) UNIQUE,
    phone_number NVARCHAR(15),
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE()
);
GO

-- Table: Products
CREATE TABLE Products (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name NVARCHAR(100) NOT NULL,
    description NVARCHAR(MAX),
    price DECIMAL(18, 2) NOT NULL,
    amount INT NOT NULL,
    category NVARCHAR(50),
    image_link NVARCHAR(MAX),
    supplier_id INT,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (supplier_id) REFERENCES Suppliers(id) ON DELETE SET NULL
);
GO

-- Table: Orders
CREATE TABLE Orders (
    id INT IDENTITY(1,1) PRIMARY KEY,
    order_price DECIMAL(18, 2) NOT NULL,
    purchase_date DATETIME DEFAULT GETDATE(),
    state BIT DEFAULT 0,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);
GO

-- Table: Order_Details
CREATE TABLE Order_Details (
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    amount INT NOT NULL,
    price DECIMAL(18, 2) NOT NULL,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES Orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE
);
GO

-- Table: Payments
CREATE TABLE Payments (
    id INT IDENTITY(1,1) PRIMARY KEY,
    order_id INT NOT NULL,
    payment_date DATETIME DEFAULT GETDATE(),
    payment_method NVARCHAR(50),
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (order_id) REFERENCES Orders(id) ON DELETE CASCADE
);
GO

-- Table: Rates
CREATE TABLE Rates (
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    star INT CHECK (star BETWEEN 1 AND 5),
    description NVARCHAR(MAX),
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    PRIMARY KEY (product_id, user_id),
    FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);
GO

-- Table: Carts
CREATE TABLE Carts (
    id INT IDENTITY(1,1) PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    amount INT NOT NULL,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE
);
GO
INSERT INTO Roles (name, description)
VALUES
    ('admin', N'Quản trị viên với đầy đủ quyền hạn'),
    ('manager', N'Quản lý sản phẩm và nhà cung cấp'),
    ('customer', N'Khách hàng thông thường');

-- Insert Users
INSERT INTO Users (username, password, fullname, address, role_name, phone_number)
VALUES
    ('admin', 'admin', N'Quản trị viên', N'123 Đường Chính, Thành phố Hồ Chí Minh', N'admin', '0909123456'),
    ('customer', 'customer', N'Nguyễn Văn A', N'45 Đường Phạm Ngũ Lão, Hà Nội', N'customer', '0987654321'),
    ('manager', 'manager', N'Hoàng Thị B', N'78 Đường Lý Thường Kiệt, Đà Nẵng', N'manager', '0938123456');

-- Insert Suppliers
INSERT INTO Suppliers (name, email, phone_number)
VALUES
    (N'Công ty Thời Trang Việt', 'lienhe@thoitrangviet.com', '028-1234-5678'),
    (N'Công ty Dệt May ABC', 'sales@detmayabc.vn', '024-9876-5432'),
    (N'Công ty Phụ Kiện Thời Trang', 'info@phukienthoitrang.com', '029-6543-2109');

-- Insert Products
INSERT INTO Products (name, description, price, amount, category, image_link, supplier_id)
VALUES
    (N'Áo sơ mi nam', N'Áo sơ mi nam kiểu dáng công sở', 350000.00, 100, N'Quần áo nam', 'link_to_image', 1),
    (N'Quần jean nữ', N'Quần jean nữ thời trang', 500000.00, 50, N'Quần áo nữ', 'link_to_image', 2),
    (N'Nón lưỡi trai', N'Nón thời trang cho nam và nữ', 150000.00, 200, N'Phụ kiện', 'link_to_image', 3);

-- Insert Orders
INSERT INTO Orders (order_price, state, user_id)
VALUES
    (850000.00, 1, 2),
    (300000.00, 0, 2);

-- Insert Order Details
INSERT INTO Order_Details (order_id, product_id, amount, price)
VALUES
    (1, 1, 2, 700000.00),
    (1, 3, 1, 150000.00),
    (2, 2, 1, 300000.00);

-- Insert Payments
INSERT INTO Payments (order_id, payment_method)
VALUES
    (1, N'Thẻ tín dụng'),
    (2, N'Tiền mặt');

-- Insert Rates
INSERT INTO Rates (product_id, user_id, star, description)
VALUES
    (1, 2, 5, N'Chất lượng tốt, rất hài lòng!'),
    (2, 2, 4, N'Sản phẩm đẹp, nhưng giao hàng hơi chậm.');

-- Insert Carts
INSERT INTO Carts (user_id, product_id, amount)
VALUES
    (2, 3, 2),
    (2, 2, 1);

select * from Carts


--Hàm
1. Hàm kiểm tra số lượng sản phẩm trong giỏ hàng
ALTER FUNCTION CheckCart (@user_id INT)
RETURNS INT
AS
BEGIN
    RETURN (SELECT SUM(amount) FROM Carts WHERE user_id = @user_id);
END;
GO

--Ví dụ: Để kiểm tra tổng số lượng sản phẩm trong giỏ hàng của người dùng có user_id = 2:

SELECT dbo.CheckCart(2) AS TotalProductCount;

2. Hàm tính tổng giá trị giỏ hàng
ALTER FUNCTION SUMCart (@user_id INT)
RETURNS DECIMAL(18, 2)
AS
BEGIN
    RETURN (SELECT SUM(Products.price * Carts.amount) 
            FROM Carts 
            JOIN Products ON Carts.product_id = Products.id 
            WHERE user_id = @user_id);
END;
GO

--Ví dụ: Để tính tổng giá trị giỏ hàng của người dùng có user_id = 2;
SELECT dbo.SUMCart(2) AS TotalCartValue;

--THỦ TỤC
1. Thủ tục thêm sản phẩm vào giỏ hàng
ALTER PROC AddToCart
    @user_id INT,
    @product_id INT,
    @amount INT
AS
BEGIN
    -- Kiểm tra sản phẩm đã tồn tại trong giỏ hàng hay chưa
    IF EXISTS (
        SELECT * FROM Carts
        WHERE user_id = @user_id AND product_id = @product_id
    )
    BEGIN
        -- Nếu sản phẩm đã tồn tại, tăng số lượng
        UPDATE Carts
        SET amount = amount + @amount
        WHERE user_id = @user_id AND product_id = @product_id;
    END
    ELSE
    BEGIN
        -- Nếu sản phẩm chưa tồn tại, thêm mới vào giỏ hàng
        INSERT INTO Carts (user_id, product_id, amount)
        VALUES (@user_id, @product_id, @amount);
    END
END;
GO

--VD:Người dùng có user_id = 2 thêm 2 sản phẩm product_id = 5 vào giỏ hàng.
EXEC AddToCart @user_id = 2, @product_id = 5, @amount = 2;

2. Thủ tục xóa giỏ hàng của người dùng
ALTER PROC ClearCart
    @user_id INT
AS
BEGIN
    -- Xóa tất cả sản phẩm trong giỏ hàng của người dùng
    DELETE FROM Carts
    WHERE user_id = @user_id;
END;
GO

--VD:Xóa toàn bộ giỏ hàng của người dùng có user_id = 2
EXEC ClearCart @user_id = 2;

--View
1. View hiển thị giỏ hàng chi tiết
ALTER VIEW CartDetails AS
SELECT 
    Carts.user_id,
    Products.name AS product_name,
    Carts.amount,
    Products.price,
    (Carts.amount * Products.price) AS total_price
FROM Carts
JOIN Products ON Carts.product_id = Products.id;
GO

--VD: HIỂN THỊ
SELECT * FROM CartDetails;

2. View tổng hợp số lượng sản phẩm trong giỏ hàng theo người dùng
ALTER VIEW CartSummary AS
SELECT 
    user_id,
    SUM(amount) AS total_items
FROM Carts
GROUP BY user_id;
GO

--VD: HIỂN THỊ 
SELECT * FROM CartSummary;

--trigger
1. Trigger kiểm tra tồn kho khi thêm sản phẩm vào giỏ hàng
ALTER TRIGGER trg_CheckStockBeforeInsert
ON Carts
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @product_id INT, @amount INT, @stock INT;

    -- Lấy thông tin từ dữ liệu chèn vào
    SELECT @product_id = product_id, @amount = amount
    FROM INSERTED;

    -- Lấy số lượng sản phẩm còn lại trong kho
    SELECT @stock = amount
    FROM Products
    WHERE id = @product_id;

    -- Kiểm tra tồn kho
    IF @amount > @stock
    BEGIN
        PRINT('Không thể thêm sản phẩm vào giỏ hàng vì số lượng vượt quá tồn kho.');
        ROLLBACK TRANSACTION;
    END
    ELSE
    BEGIN
        -- Chèn vào bảng Carts nếu hợp lệ
        INSERT INTO Carts (user_id, product_id, amount, created_at, updated_at)
        SELECT user_id, product_id, amount, created_at, updated_at
        FROM INSERTED;
    END
END;
GO

2. Trigger cập nhật thời gian chỉnh sửa giỏ hàng
ALTER TRIGGER trg_UpdateCartTimestamp
ON Carts
AFTER UPDATE
AS
BEGIN
    -- Cập nhật cột updated_at khi giỏ hàng được chỉnh sửa
    UPDATE Carts
    SET updated_at = GETDATE()
    FROM Carts
    INNER JOIN INSERTED
    ON Carts.id = INSERTED.id;
END;
GO

--CON TRỎ
1. Con trỏ duyệt qua giỏ hàng và giảm số lượng trong kho sau khi thanh toán
ALTER PROCEDURE ProcessCartPayment
    @user_id INT
AS
BEGIN
    DECLARE @cart_id INT, @product_id INT, @amount INT;

    -- Con trỏ duyệt qua các sản phẩm trong giỏ hàng của người dùng
    DECLARE CartCursor CURSOR FOR
    SELECT id, product_id, amount
    FROM Carts
    WHERE user_id = @user_id;

    OPEN CartCursor;

    FETCH NEXT FROM CartCursor INTO @cart_id, @product_id, @amount;

    WHILE @@FETCH_STATUS = 0
    BEGIN
        -- Giảm số lượng trong kho
        UPDATE Products
        SET amount = amount - @amount
        WHERE id = @product_id;

        -- Xóa sản phẩm khỏi giỏ hàng
        DELETE FROM Carts
        WHERE id = @cart_id;

        FETCH NEXT FROM CartCursor INTO @cart_id, @product_id, @amount;
    END;

    CLOSE CartCursor;
    DEALLOCATE CartCursor;
END;
GO

-- VD: Gọi thủ tục để xử lý thanh toán giỏ hàng cho người dùng có `user_id = 1`
EXEC ProcessCartPayment @user_id = 1;

2. Con trỏ kiểm tra sản phẩm tồn kho thấp
ALTER PROCEDURE CheckLowStock
    @threshold INT
AS
BEGIN
    DECLARE @product_id INT, @name NVARCHAR(100), @amount INT;

    -- Con trỏ duyệt qua tất cả các sản phẩm
    DECLARE ProductCursor CURSOR FOR
    SELECT id, name, amount
    FROM Products;

    OPEN ProductCursor;

    FETCH NEXT FROM ProductCursor INTO @product_id, @name, @amount;

    WHILE @@FETCH_STATUS = 0
    BEGIN
        -- Kiểm tra nếu số lượng trong kho thấp hơn ngưỡng
        IF @amount < @threshold
        BEGIN
            PRINT 'Sản phẩm "' + @name + '" (ID: ' + CAST(@product_id AS NVARCHAR) + ') có tồn kho thấp: ' + CAST(@amount AS NVARCHAR);
        END;

        FETCH NEXT FROM ProductCursor INTO @product_id, @name, @amount;
    END;

    CLOSE ProductCursor;
    DEALLOCATE ProductCursor;
END;
GO

--VD: Gọi thủ tục để kiểm tra các sản phẩm có tồn kho thấp hơn ngưỡng 10
EXEC CheckLowStock @threshold = 10;





