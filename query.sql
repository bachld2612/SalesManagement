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


-- state:
-- 0: đơn hàng đang chuẩn bị
-- 1: đơn hàng đang vận chuyển
-- 2: đã vận chuyển và thanh toán
-- 3: đã huỷ
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



-- member_tier
-- 5 tier:
-- 0 đồng: standard
-- 50000 đồng: broze
-- 100000 đồng: silver
-- 500000 đồng: gold
-- 1000000 đồng: platinum

-- role:
-- admin
-- store manager
-- shipper
-- customer

select * from view_ShowIncome