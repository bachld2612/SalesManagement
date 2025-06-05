-- TRIGGER

-- update the price of the order when it is changed
create trigger trig_onInsertOrUpdateOrder
on order_details
for insert, update
as
	declare @orderId int
	select distinct @orderId = order_id 
	from inserted
	declare @customerId int
	select @customerId = Orders.user_id from Orders where id = @orderId
	declare @price decimal(18,2)
	select @price = sum(Order_Details.amount * Products.price)
	from Order_Details join Products on Order_Details.product_id = Products.id
	where order_id = @orderId
	declare @tier nvarchar(30)
	select @tier = dbo.fn_getTierCustomer(@customerId)
	if(@tier = 'Bronze') set @price = @price * 1.03
	else if (@tier = 'Silver') set @price = @price * 1.05
	else if (@tier = 'Gold') set @price = @price * 1.07
	else if (@tier = 'Platinum') set @price = @price * 1.1
	else set @price = @price * 1
	update Orders set order_price = @price
	where id = @orderId

-- update the price when a certain product is deleted from the order
create trigger trig_onDeleteProductFromOrders
on order_details
for delete
as
	declare @orderId int
	select distinct @orderId = order_id 
	from deleted
	declare @customerId int
	select @customerId = Orders.user_id from Orders where id = @orderId
	declare @price decimal(18,2)
	select @price = sum(Order_Details.amount * Products.price)
	from Order_Details join Products on Order_Details.product_id = Products.id
	where order_id = @orderId
	declare @tier nvarchar(30)
	select @tier = dbo.fn_getTierCustomer(@customerId)
	if(@tier = 'Bronze') set @price = @price * 1.03
	else if (@tier = 'Silver') set @price = @price * 1.05
	else if (@tier = 'Gold') set @price = @price * 1.07
	else if (@tier = 'Platinum') set @price = @price * 1.1
	else set @price = @price * 1
	update Orders set order_price = @price
	where id = @orderId

-- Prevent from updating or inserting a product into the order that has been delivered
create trigger trig_preventAddWhenShip
on Order_Details
for insert, update
as
	declare @orderId int
	select distinct @orderId = order_id
	from inserted
	declare @state int
	select distinct @state = state 
	from inserted join Orders on Orders.id = inserted.order_id
	if @state = 1 or @state = 2
		rollback transaction
-- Prevent from deleting a product from the order that has been delivered
create trigger trig_preventDeleteWhenShip
on Order_Details
for delete
as
	declare @orderId int
	select distinct @orderId = order_id
	from deleted
	declare @state int
	select distinct @state = state 
	from deleted join Orders on Orders.id = deleted.order_id
	if @state = 1 or @state = 2
		rollback transaction

-- update the updated_at column when make changes
create trigger trig_updateTimeWhenChange
on Order_Details
for insert, update
as
	declare @orderId int
	select distinct @orderId = order_id
	from inserted
	update Orders set updated_at = getdate()
	where Orders.id = @orderId

create trigger trig_onUpdateState
on Orders
for update
as
	declare @orderId int
	select distinct @orderId = id
	from inserted
	update Orders set updated_at = getdate()
	where Orders.id = @orderId

create trigger trig_updateTimeWhenDelete
on Order_Details
for delete
as
	declare @orderId int
	select distinct @orderId = order_id
	from deleted
	update Orders set updated_at = getdate()
	where Orders.id = @orderId


--FUNCTION

--get the tier of a certain customer
create function fn_getTierCustomer (@customerId int)
returns nvarchar(30)
as begin
	declare @tier nvarchar(30)
	select @tier = Users.membership_tier from Users
	where @customerId = Users.id
	return @tier
end

-- get the money that a customer has bought
create function fn_getTotalMoney (@customer_id int)
returns decimal(18, 2)
as begin
	declare @total decimal(18, 2)
	select @total = sum(Orders.order_price)
	from Orders join Users on Orders.user_id = Users.id
	where Orders.user_id = @customer_id and Orders.state = 1 or Orders.state = 2
	return @total
end

-- get the money that the shop earns in a certain month
alter function getMoneyInAMonth (@month int, @year int)
returns decimal(18, 2)
as begin
	declare @total decimal(18, 2)
	select @total = sum(Orders.order_price)
	from Orders join Users on Orders.user_id = Users.id
	where month(Orders.updated_at) = @month and year(Orders.updated_at) = @year
	and (state = 1 or state = 2)
	return @total

end



-- get the money that have been paid
create function getPaidMoney ()
returns decimal(18,2)
as begin
	declare @total decimal(18,2)
	select @total = sum(Orders.order_price)
			from Orders
			where state = 2
	return @total
end	

create function getUnPaidMoney ()
returns decimal(18,2)
as begin
	declare @total decimal(18,2)
	select @total = sum(Orders.order_price)
			from Orders
			where state = 0 or state = 1
	return @total
end	

--VIEW
-- show order info
create view view_OrderInfo
as
	select Orders.id as order_id, Orders.user_id, Users.fullname, Users.address, Users.phone_number, Orders.order_price, Orders.state, Orders.purchase_date
	from Orders join Users on Users.id = Orders.user_id

--show the income for each month
create view view_ShowIncome
as 
	select FORMAT(purchase_date, 'yyyy-MM') as month,
			sum(order_price) as income
	from Orders
	where state = 2 or state = 1
	group by FORMAT(purchase_date, 'yyyy-MM')

--show the products that have the most buy times
create view view_ShowProduct
as
	select Order_Details.product_id, Products.name, 
			SUM(Order_Details.amount) as [total buy time]
	from Order_Details join Products on Order_Details.product_id = Products.id
	join Orders on Orders.id = Order_Details.order_id
	where state = 1 or state = 2
	group by Order_Details.product_id, Products.name
	having SUM(Order_Details.amount) = (
		select top 1 SUM(Order_Details.amount) as [max time]
		from Order_Details join Orders on Order_Details.order_id = Orders.id
		where state = 1 or state = 2
		group by Order_Details.product_id
		order by [max time] DESC
	)

-- Show the detail info from order details
create view view_ShowOrderDetails
as
	select Order_Details.order_id, Order_Details.product_id,Products.name,
	Products.price, Order_Details.amount, Products.image_link, 
	Order_Details.amount * Products.price as [total_price]
	from Order_Details join Products on Order_Details.product_id = Products.id

--PROCEDURE
--show the detail info of a certain orders
create procedure sp_ShowDetailInfo @orderId int
as begin
	declare @customerName nvarchar(100), @address nvarchar(MAX),
			@phoneNumber nvarchar(15), @orderPrice decimal(18, 2),
			@purchaseDate datetime
	select @customerName = fullname, @address = address,
		   @phoneNumber = phone_number, @orderPrice = order_price,
		   @purchaseDate = purchase_date
	from view_OrderInfo
	where order_id = @orderId
	declare cur_info cursor scroll 
	for
		select name, price, amount, total_price from view_ShowOrderDetails
		where order_id = @orderId
	declare @name nvarchar(100), @price decimal(18,2), @amount int,
	@totalPrice decimal(18,2)
	open cur_info
	print N'Tên khách hàng: ' + @customerName
	print N'Địa chỉ: ' + @address
	print N'Số điện thoại: ' + @phoneNumber
	print N'Ngày thanh toán: ' + cast(FORMAT(@purchaseDate, 'd') as nvarchar)
	fetch first from cur_info into @name, @price, @amount, @totalPrice
	while @@FETCH_STATUS = 0
	begin
		print N'Tên sản phẩm: ' + @name
		print N'Đơn giá: ' + cast(@price as nvarchar)
		print N'Số lượng: ' + cast(@amount as nvarchar)
		print N'Thành tiền: ' + cast(@totalPrice as nvarchar)
		print '---------'
		fetch next from cur_info into @name, @price, @amount, @totalPrice
	end
	print N'Tổng tiền hoá đơn: ' + cast(@orderPrice as nvarchar)
	close cur_info
	deallocate cur_info
end

-- show the orders that haven't been delivered
create procedure sp_showUndeliveredOrders
as begin
	declare cur_undeliveredOrderId cursor scroll
	for
		select Orders.id from Orders
		where state = 0
	open cur_undeliveredOrderId
	declare @orderId int
	fetch first from cur_undeliveredOrderId into @orderId
	while @@FETCH_STATUS = 0
	begin
		exec sp_ShowDetailInfo @orderId
		print '--------------------'
		fetch next from cur_undeliveredOrderId into @orderId
	end
	close cur_undeliveredOrderId
	deallocate cur_undeliveredOrderId
end

-- show the orders that were delivered
create procedure sp_showDeliveredOrders
as begin
	declare cur_deliveredOrderId cursor scroll
	for
		select Orders.id from Orders
		where state = 1 or state = 2
	open cur_deliveredOrderId
	declare @orderId int
	fetch first from cur_deliveredOrderId into @orderId
	while @@FETCH_STATUS = 0
	begin
		exec sp_ShowDetailInfo @orderId
		print '--------------------'
		fetch next from cur_deliveredOrderId into @orderId
	end
	close cur_deliveredOrderId
	deallocate cur_deliveredOrderId
end
exec sp_showDeliveredOrders

--CURSOR
-- cursor to show the product that havent been bought
declare cur_product cursor scroll 
for
	select Products.id, Products.name as [product name], Products.price, 
		   Products.category, Suppliers.name as [supplier name]
	from Products join Suppliers on Products.supplier_id = Suppliers.id
	where not Products.id in (
		select distinct Order_Details.product_id from Order_Details
	)
open cur_product
declare @id int, @productName nvarchar(100), @category nvarchar(50), 
		@supplierName nvarchar(100), @price decimal(18,2)
fetch first from cur_product into @id, @productName, @price, @category, @supplierName
print N'Các sản phẩm chưa có khách hàng mua: '
while @@FETCH_STATUS = 0
begin
	print N'Mã sản phẩm: ' + cast(@id as nvarchar)
	print N'Tên sản phẩm: ' + @productName
	print N'Loại sản phẩm: ' + @category
	print N'Giá bán: ' + cast(@price as nvarchar)
	print N'Nhà cung cấp: ' + @supplierName
	print '----------------'
	fetch next from cur_product into @id, @productName, @price, @category, @supplierName
end
close cur_product
deallocate cur_product

-- cursor to show the products that have the most buy time
declare cur_mostProduct scroll cursor 
for
	select * from view_ShowProduct
open cur_mostProduct
declare @productId int, @name nvarchar(100), @buyTime int
fetch first from cur_mostProduct into @productId, @name, @buyTime
print N'Các sản phẩm bán chạy nhất với số lượt mua là: ' + cast(@buyTime as nvarchar) + N' lần'
while @@FETCH_STATUS = 0
begin
	print N'Sản phẩm có mã là: ' + cast(@productId as nvarchar) + N', tên sản phẩm: ' + @name
	fetch next from cur_mostProduct into @productId, @name, @buyTime
end
close cur_mostProduct
deallocate cur_mostProduct


