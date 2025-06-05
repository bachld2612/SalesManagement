


CREATE TABLE Roles (
    name NVARCHAR(50) PRIMARY KEY, -- Role name as the ID
    description NVARCHAR(255) NULL
);
GO

-- Insert roles into Roles table
INSERT INTO Roles (name, description)
VALUES 
    ('customer', 'A regular customer with access to basic features.'),
    ('staff', 'An employee responsible for managing operations.'),
    ('manager', 'A manager with access to administrative functions.');


-- Table: Users
CREATE TABLE Users (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username NVARCHAR(50) NOT NULL UNIQUE,
    password NVARCHAR(255) NOT NULL,
    fullname NVARCHAR(100),
    address NVARCHAR(MAX),
    role_name NVARCHAR(50) NOT NULL, -- Reference to Roles table
	membership_tier NVARCHAR(30),
    phone_number NVARCHAR(15),
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (role_name) REFERENCES Roles(name)
);
GO


-- Tạo bảng NotificationLog để lưu thông báo đã gửi
CREATE TABLE NotificationLog (
    id INT IDENTITY(1,1) PRIMARY KEY,
    user_id INT NOT NULL,
    message NVARCHAR(255),
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES Users(id)
);

----------------------------------TRIGGER

--trig1 thêm thông báo khi tạo tài khoản thành công
CREATE TRIGGER trg_NewUserNotification
ON Users
AFTER INSERT
AS
BEGIN
    INSERT INTO NotificationLog (user_id, message)
    SELECT id, CONCAT('Welcome ', username, '! Thank you for joining as a ', role_name, '.')
    FROM inserted;
END;
GO

/*
-- Test trigger
INSERT INTO Users (username, password, role_name)
VALUES ('john_doe', 'password123', 'customer');

SELECT * FROM NotificationLog;
*/


/*
TRIGGER 2
Cảnh báo khi xóa role có user liên quan
*/
ALTER TRIGGER trg_PreventDeleteRole
ON Roles
INSTEAD OF DELETE
AS
BEGIN
    -- Kiểm tra nếu có người dùng liên quan đến vai trò cần xóa
    IF EXISTS (
        SELECT *
        FROM Users
        WHERE role_name IN (SELECT name FROM deleted)
    )
    BEGIN
        -- In ra thông báo và ngăn việc xóa vai trò
        PRINT 'Cannot delete this role because it is being used by one or more users.';
        ROLLBACK TRANSACTION;
    END
    ELSE
    BEGIN
        DELETE FROM Roles
        WHERE name IN (SELECT name FROM deleted);
        PRINT 'Role deleted successfully.';
    END
END;


/*
TEST TRIGGER 1
DELETE FROM dbo.Roles
WHERE name = 'customer'
*/

--------------------------------------------------------------

/*
TRIGGER 3
Cập nhập cột updated_at 
*/

Alter TRIGGER trg_UpdateUserTimestamp
ON Users
AFTER UPDATE
AS
BEGIN
    -- Ngăn trigger tự kích hoạt lại
    IF (NOT UPDATE(updated_at))
    BEGIN
        UPDATE Users
        SET updated_at = GETDATE()
        WHERE id IN (SELECT id FROM inserted);
    END
END;
GO
/*
TEST TRIGGER 2
SELECT * FROM dbo.Users
UPDATE Users SET role_name = 'customer' WHERE id = 6
SELECT * FROM dbo.Users
*/


-------------------------------------------------------------------------


/*
TRIGGER 4
"Trigger để đảm bảo membership_tier được
 đặt mặc định thành 'stander' nếu 
vai trò là 'customer'"
*/

ALTER TRIGGER trg_DefaultMembershipTier
ON Users
AFTER INSERT
AS
BEGIN
    UPDATE Users
    SET membership_tier = 'stander'
    WHERE role_name = 'customer' AND (membership_tier IS NULL OR membership_tier = '');
END;
GO

/*
TEST TRIGGER 3

INSERT INTO dbo.Users
(
    username,
    password,
    fullname,
    address,
    role_name,
    membership_tier,
    phone_number,
    created_at,
    updated_at
)
VALUES
(   N'kieuanh',     -- username - nvarchar(50)
    N'1234',     -- password - nvarchar(255)
    NULL,    -- fullname - nvarchar(100)
    NULL,    -- address - nvarchar(max)
    N'customer',     -- role_name - nvarchar(50)
    NULL,    -- membership_tier - nvarchar(30)
    NULL,    -- phone_number - nvarchar(15)
    DEFAULT, -- created_at - datetime
    DEFAULT  -- updated_at - datetime
    )


SELECT * FROM	 dbo.Users

*/



--trigger 5 cập nhật memebertier
ALTER TRIGGER trg_UpdateMembershipTier
ON Orders
AFTER INSERT, UPDATE
AS
BEGIN
    DECLARE @user_id INT;
    DECLARE @total DECIMAL(18, 2);
    DECLARE @new_tier NVARCHAR(50);

    -- Lấy user_id từ bản ghi mới
    SELECT @user_id = user_id FROM inserted;

    -- Tính tổng số tiền tiêu dùng của người dùng
    SET @total = dbo.fn_getTotalMoney(@user_id);

    -- Xác định bậc membership mới dựa trên tổng số tiền tiêu dùng
    IF @total >= 1000000
        SET @new_tier = 'Platinum';
    ELSE IF @total >= 500000
        SET @new_tier = 'Gold';
    ELSE IF @total >= 100000
        SET @new_tier = 'Silver';
    ELSE IF @total >= 50000
        SET @new_tier = 'Bronze';
	ELSE
		SET @new_tier = 'Standard';

    -- Cập nhật bậc membership trong bảng Users
    UPDATE Users
    SET membership_tier = @new_tier
    WHERE id = @user_id;
END;
GO

--DROP TRIGGER trg_UpdateMembershipTier

SELECT * FROM dbo.Users








------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------ FUNCTION ------------------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
/*
FUNCTION 
*/

-- Function 1: Count users by role đếm số lượng user theo role
ALTER FUNCTION fn_CountUsersByRole ()
RETURNS TABLE
AS
RETURN
    SELECT role_name, COUNT(*) AS user_count
    FROM Users
    GROUP BY role_name;
GO

/*
SELECT * 
FROM fn_CountUsersByRole();
*/


-- Function 2: Check if a user is an admin (kiểm tra xem có phải admin ko)

ALTER FUNCTION fn_CheckUserRole (
    @user_id INT,
    @role_name NVARCHAR(50)
)
RETURNS BIT
AS
BEGIN
    DECLARE @is_in_role BIT = 0;

    IF EXISTS (
        SELECT *
        FROM Users
        WHERE id = @user_id AND role_name = @role_name
    )
    BEGIN
        SET @is_in_role = 1;
    END

    RETURN @is_in_role;
END;
GO

-- Sử dụng
SELECT dbo.fn_CheckUserRole(1, 'manager'); 

-- FUNCTION 3: Đếm số thông báo của người dùng
ALTER FUNCTION fn_CountNotifications (
    @user_id INT
)
RETURNS INT
AS
BEGIN
    RETURN (
        SELECT COUNT(*)
        FROM NotificationLog
        WHERE user_id = @user_id
    );
END;
GO
SELECT dbo.fn_CountNotifications(1);
/*
DROP FUNCTION fn_IsAdmin
SELECT username
FROM Users
WHERE dbo.fn_IsManager(username) != 1;
SELECT * FROM dbo.Users
*/


--function hiển thị top 10 người tiêu nhiều nhất
CREATE FUNCTION GetTop10Spenders()
RETURNS TABLE
AS
RETURN
(
    SELECT TOP 10
        UserID,
        Username,
        Fullname,
        MembershipTier,
        PhoneNumber,
        TotalSpent
    FROM
        Spenders
    ORDER BY
        TotalSpent DESC
);


SELECT * FROM dbo.GetTop10Spenders();






------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------ PROC ------------------------------------------------------------------------------------------------------------------------------------
---------------------



--Procedure 1 để trả về tất cả người dùng với một vai trò cụ thể
ALTER PROCEDURE sp_GetUsersByRole
    @RoleName NVARCHAR(50)
AS
BEGIN
    SELECT id, username, fullname, address, role_name, phone_number, created_at, updated_at
    FROM Users
    WHERE role_name = @RoleName;
END;

EXEC sp_GetUsersByRole @RoleName = 'customer'


--Procedure 2 để cập nhật vai trò của một người dùng
ALTER PROCEDURE sp_UpdateUserRole
    @user_id INT,
    @new_role NVARCHAR(50)
AS
BEGIN
    -- Kiểm tra vai trò có tồn tại không
    IF NOT EXISTS (SELECT * FROM Roles WHERE name = @new_role)
    BEGIN
        PRINT 'Invalid role: ' + @new_role;
        RETURN;
    END;

    -- Cập nhật vai trò người dùng
    UPDATE Users
    SET role_name = @new_role
    WHERE id = @user_id;

    -- Thông báo cập nhật thành công
    PRINT 'User role updated successfully.';
END;
GO

--test 
EXEC sp_UpdateUserRole @user_id = 6, @new_role = 'manager';
SELECT * FROM dbo.Users


-- PROC 3 hiển thị 10 thông báo gần nhất
CREATE PROCEDURE sp_GetNotificationsByUser
    @user_id INT
AS
BEGIN
    SELECT TOP 10 -- Giới hạn 10 thông báo gần nhất
        message,
        created_at
    FROM NotificationLog
    WHERE user_id = @user_id
    ORDER BY created_at DESC;
END;
GO
-- Test procedure
EXEC sp_GetNotificationsByUser @user_id = 1;

--PROC 4 proc xóa thông báo theo số ngày 
CREATE PROCEDURE DeleteOldNotifications
    @delete_date DATETIME
AS
BEGIN
    DECLARE @notification_id INT;
    DECLARE notification_cursor CURSOR FOR
    SELECT id
    FROM NotificationLog
    WHERE created_at < @delete_date;

    -- Mở con trỏ
    OPEN notification_cursor;

    -- Lấy bản ghi đầu tiên
    FETCH NEXT FROM notification_cursor INTO @notification_id;

    -- Duyệt qua từng thông báo
    WHILE @@FETCH_STATUS = 0
    BEGIN
        -- Xóa thông báo cũ
        DELETE FROM NotificationLog
        WHERE id = @notification_id;

        -- Lấy bản ghi tiếp theo
        FETCH NEXT FROM notification_cursor INTO @notification_id;
    END;

    -- Đóng và giải phóng con trỏ
    CLOSE notification_cursor;
    DEALLOCATE notification_cursor;
END;



--proc 5 gửi thông báo theo role
CREATE PROCEDURE SendNotificationsByRole
    @role_name NVARCHAR(50),
    @custom_message NVARCHAR(255)
AS
BEGIN
    DECLARE user_cursor CURSOR FOR
    SELECT id, username
    FROM Users
    WHERE role_name = @role_name;

    DECLARE @user_id INT;
    DECLARE @username NVARCHAR(50);
    DECLARE @final_message NVARCHAR(255);

    -- Mở con trỏ
    OPEN user_cursor;

    -- Lấy bản ghi đầu tiên
    FETCH NEXT FROM user_cursor INTO @user_id, @username;

    -- Vòng lặp qua từng bản ghi
    WHILE @@FETCH_STATUS = 0
    BEGIN
        -- Tùy chỉnh thông báo cho từng người dùng
        SET @final_message = CONCAT(@custom_message, ' ', @username);

        -- Ghi thông báo vào bảng NotificationLog
        INSERT INTO NotificationLog (user_id, message)
        VALUES (@user_id, @final_message);

        -- Hiển thị thông báo xử lý
        PRINT CONCAT('Notification sent to user: ', @username, ' (ID: ', @user_id, ')');

        -- Lấy bản ghi tiếp theo
        FETCH NEXT FROM user_cursor INTO @user_id, @username;
    END;

    -- Đóng và giải phóng con trỏ
    CLOSE user_cursor;
    DEALLOCATE user_cursor;

    PRINT 'All notifications have been processed.';
END;
GO




-- View 1: Users with roles
ALTER VIEW vw_UsersWithRoles
AS
SELECT 
    u.id,
    u.username,
    u.fullname,
    r.description AS role_description,
    u.membership_tier,
    u.phone_number,
    u.created_at,
    u.updated_at
FROM Users u
JOIN Roles r ON u.role_name = r.name;
GO


SELECT * FROM vw_UsersWithRoles



-- View 2: Recently created users
ALTER VIEW vw_RecentUsers
AS
SELECT *
FROM Users
WHERE created_at >= DATEADD(DAY, -7, GETDATE());
GO

SELECT * FROM vw_RecentUsers


--view 3 hiển thị chi tiêu của người ùng
ALTER VIEW Spenders AS
SELECT
    Users.id AS UserID,
    Users.username AS Username,
    Users.fullname AS Fullname,
    Users.membership_tier AS MembershipTier,
    Users.phone_number AS PhoneNumber,
    SUM(Orders.order_price) AS TotalSpent
FROM
    Orders
JOIN
    Users ON Orders.user_id = Users.id
WHERE
    Orders.state = 1 OR Orders.state = 2
GROUP BY
    Users.id,
    Users.username,
    Users.fullname,
    Users.membership_tier,
    Users.phone_number


--CUR1
-- Tạo con trỏ để gửi thông báo cho từng người dùng
DECLARE user_cursor CURSOR FOR
SELECT id, username
FROM Users
WHERE role_name = @role_name;

DECLARE @user_id INT;
DECLARE @username NVARCHAR(50);
DECLARE @final_message NVARCHAR(255);

-- Mở con trỏ
OPEN user_cursor;

-- Lấy bản ghi đầu tiên
FETCH NEXT FROM user_cursor INTO @user_id, @username;

-- Vòng lặp qua từng bản ghi
WHILE @@FETCH_STATUS = 0
BEGIN
    -- Tùy chỉnh thông báo cho từng người dùng
    SET @final_message = CONCAT(@custom_message, ' ', @username);

    -- Ghi thông báo vào bảng NotificationLog
    INSERT INTO NotificationLog (user_id, message)
    VALUES (@user_id, @final_message);

    -- Hiển thị thông báo xử lý
    PRINT CONCAT('Notification sent to user: ', @username, ' (ID: ', @user_id, ')');

    -- Lấy bản ghi tiếp theo
    FETCH NEXT FROM user_cursor INTO @user_id, @username;
END;

-- Đóng và giải phóng con trỏ
CLOSE user_cursor;
DEALLOCATE user_cursor;

PRINT 'All notifications have been processed.';



--CUR2
--con trỏ để xóa thông báo
-- Con trỏ lấy các thông báo cũ hơn ngày cần xóa
DECLARE notification_cursor CURSOR FOR
SELECT id
FROM NotificationLog
WHERE created_at < @delete_date;

-- Mở con trỏ
OPEN notification_cursor;

-- Lấy bản ghi đầu tiên
FETCH NEXT FROM notification_cursor INTO @notification_id;

-- Duyệt qua từng thông báo
WHILE @@FETCH_STATUS = 0
BEGIN
    -- Xóa thông báo cũ
    DELETE FROM NotificationLog
    WHERE id = @notification_id;

    -- Lấy bản ghi tiếp theo
    FETCH NEXT FROM notification_cursor INTO @notification_id;
END;

-- Đóng và giải phóng con trỏ
CLOSE notification_cursor;
DEALLOCATE notification_cursor;






