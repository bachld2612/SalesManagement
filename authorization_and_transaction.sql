

-- Tạo các Role cho hệ thống
CREATE ROLE Admin; -- Quản lý hệ thống
CREATE ROLE StoreManager; -- Quản lý cửa hàng
CREATE ROLE Customer; -- Khách hàng
CREATE ROLE Shipper; -- Shipper
GO

--Admin có toàn quyền trên tất cả các bảng và cơ sở dữ liệu:
GRANT CONTROL ON DATABASE::SalesManagement TO Admin;
GO


--2. Phân quyền cụ thể cho từng Role
--Phân quyền dựa trên các bảng đã tạo trong cơ sở dữ liệu:

-- Role Admin
--Admin có toàn quyền trên tất cả các bảng và cơ sở dữ liệu: sql

GRANT CONTROL ON DATABASE::SalesManagement TO Admin;
GO
-- Role StoreManager
--Quản lý cửa hàng có quyền thao tác trên bảng liên quan đến sản phẩm, đơn hàng, và nhà cung cấp:
GRANT SELECT, INSERT, UPDATE, DELETE ON Products TO StoreManager;
GRANT SELECT, INSERT, UPDATE, DELETE ON Orders TO StoreManager;
GRANT SELECT, INSERT, UPDATE, DELETE ON Suppliers TO StoreManager;
GRANT SELECT, UPDATE, INSERT, DELETE ON Users TO StoreManager; -- Quản lý thông tin khách hàng (không sửa mật khẩu)
GRANT SELECT, UPDATE, INSERT, DELETE ON Payments TO StoreManager;
GRANT SELECT ON rates TO StoreManager;
GO


--Khách hàng có quyền xem và thêm vào giỏ hàng, đánh giá sản phẩm, tạo đơn hàng và danh sách yêu thích:
GRANT SELECT ON Products TO Customer; -- Xem sản phẩm
GRANT INSERT, UPDATE, DELETE ON Carts TO Customer; -- Quản lý giỏ hàng
GRANT INSERT, UPDATE, DELETE ON Orders TO Customer; -- Tạo, chỉnh sửa và xoá đơn hàng (nếu cần)
GRANT INSERT, UPDATE, DELETE ON Rates TO Customer; -- Đánh giá sản phẩm
GRANT INSERT, DELETE ON FavouriteLists TO Customer; -- Danh sách yêu thích
GO

--Shipper có quyền xem và cập nhật trạng thái các đơn hàng:
GRANT SELECT ON Order_Details TO Shipper; 
GRANT SELECT ON Products TO Shipper; 
GRANT SELECT, UPDATE ON Orders TO Shipper; -- Xem và cập nhật trạng thái đơn hàng
GO

-- Tạo Login và User
CREATE LOGIN admin_user WITH PASSWORD = 'Admin';
CREATE USER admin_user FOR LOGIN admin_user;
EXEC sp_addrolemember 'Admin', 'admin_user';

CREATE LOGIN manager_user WITH PASSWORD = 'Manager';
CREATE USER manager_user FOR LOGIN manager_user;
EXEC sp_addrolemember 'StoreManager', 'manager_user';

CREATE LOGIN customer_user WITH PASSWORD = 'Customer';
CREATE USER customer_user FOR LOGIN customer_user;
EXEC sp_addrolemember 'Customer', 'customer_user';

CREATE LOGIN shipper_user WITH PASSWORD = 'Shipper';
CREATE USER shipper_user FOR LOGIN shipper_user;
EXEC sp_addrolemember 'Shipper', 'shipper_user';
GO

-- serializable: chặn không cho phép mọi giao dịch không được thực thi
-- tránh dirty read unrepeatable read và phantoms
-- t1 đọc dữ liệu 2 lần
-- t2 chèn hoặc sửa dữ liệu trong quá trình đọc vào bảng orders 
-- dẫn tới thống kê doanh số bị thay đổi
-- các bước thực thi:
-- t1 bắt đầu transaction và ngăn chặn t2 thực thi
-- khi t1 commit t2 mới được phép thực thi

-- t1 đọc dữ liệu 2 lần:
begin transaction
set transaction isolation level serializable
select * 
from view_ShowIncome
waitfor delay '00:00:10'
select * 
from view_ShowIncome
commit transaction

--t2 thêm 1 sản phẩm vào orders dẫn tới sự thay đổi trong view doanh số
begin transaction 
insert into Orders(order_price, purchase_date,user_id, state)
values(200000000, '2025-01-10', 17, 2)
update Orders set order_price = 10000 where user_id = 17
waitfor delay '00:00:10'
rollback transaction

-- read commited: ngăn ngừa trường hợp dirty read và lost update
-- t2 chèn dữ liệu vào bảng products và đợi 10s sau đó rollback
-- t1 đọc dữ liệu products nhưng vì level read committed nên phải đợi
-- sản phẩm được thêm vào xong mới tiếp tục cho đọc
-- kết thúc t1 đọc được sản phẩm sau khi t2 hoàn thành giao dịch

--t1 đọc sản phẩm:
begin transaction
set transaction isolation level read committed
select * from Products
commit transaction

-- t2 chèn sản phẩm:
begin transaction
insert into Products(name, description, price, amount, category, image_link,
					supplier_id, buy_price, favorite_count) values
(N'Sản phẩm thử', N'Mô tả sản phẩm thử', 30000, 100, N'Thể loại thử', 'test_img_link', 1, 10000,10)
waitfor delay '00:00:10'
rollback transaction

-- dùng serializable cho việc đọc dữ liệu từ view_showProducts

-- t1 đọc dữ liệu từ view

begin transaction
set transaction isolation level serializable
select * from view_ShowProduct
commit transaction

-- t2 thực hiện insert value vào bảng order_details
begin transaction
insert into Order_Details values (40, 3, 100)
waitfor delay '00:00:10'
rollback transaction





