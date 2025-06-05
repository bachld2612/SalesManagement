use SalesManagement



--trigger




-- cap nhat so luong san pham khi 1 san pham dc them vao order detail
alter trigger update_amount_product
on Order_Details
for insert
as begin
   UPDATE Products
    SET amount = Products.amount - inserted.amount
    FROM Products,inserted
    WHERE Products.id IN (
        SELECT product_id
        FROM inserted
    );

	if exists(
	    select 1
		from Products
		where amount < 0
	)
	begin
	   rollback tran
	   print 'het me hang roi !!'
	end
end



-- cap nhat gia ban cua san pham khi them 1 san pham vao
alter trigger update_price
on Products
for insert,update
as begin
   update Products
   set Products.price = inserted.buy_price * 1.1
   from Products, inserted
   where Products.id = inserted.id
end



--view


--in ra tong gia tri nhap theo thang
alter view sumary_products_bymonth
as
select FORMAT(created_at,'yyyy-mm') as my , sum(price * amount) as total
from Products
where created_at is not null
group by FORMAT(created_at,'yyyy-mm')





-- in ra muc gia tri cua cac nha cung cap
alter view suppier_total_value
as
select Suppliers.id as supplier_id, Suppliers.name as supplier_name, email as supplier_email, phone_number as supplier_phone_number,
sum(buy_price * amount) as totalSupplierValue
from Suppliers, Products
where Suppliers.id = Products.supplier_id
group by Suppliers.id, Suppliers.name, Suppliers.email, Suppliers.phone_number
order by totalSupplierValue





--procedure


-- tang hoac giam gia cua cac san pham cua 1 nha cung cap
alter proc update_price_from_supplier
@supplierid int,
@percentChange decimal(5,2)
as begin
   if not exists(
      select 1
	  from Suppliers
	  where id = @supplierid
   )begin
      print 'ko ton tai'
	  return;
   end

   update Products
   set buy_price = buy_price * (1 + @percentChange/100)
   where supplier_id = @supplierid

   print 'cap nhat thanh cong'
end


-- lay san pham co gia tri va so luong lon hon dau vao
alter proc get_product_byPrice_andAmount
@minprice decimal(18,2),
@minamount int
as begin
   select id,name,description,price,amount,category,image_link,supplier_id,created_at,updated_at,buy_price
   from Products
   where price > @minprice
   and amount > @minamount
end



--function



-- in ra tong gia tri san pham da nhap cua 1 nha cc
alter function get_total_inventory( @supplier_id int)
returns decimal(18,2)
as begin
   declare @total decimal(18,2)

   select @total = sum(price * amount)
   from Products
   where supplier_id = @supplier_id

   return isnull(@total,0)
end



-- kiem tra 1 san pham co phai la san pham co gia tri cao nhat cua 1 nha cc nao do
alter function inspect_product_highest_value(@product_id int, @supplier_id int)
returns bit
as begin
   declare @x bit = 0
   declare @currentSupplierID int
   declare @maxProductValue decimal(18,2)
   declare @productValue decimal(18,2)

   declare cs_supplier cursor scroll
   for select id from Suppliers

   open cs_supplier
   fetch next from cs_supplier into @currentSupplierID

   while (@@FETCH_STATUS = 0) begin
      select @maxProductValue = max(price * amount)
	  from Products
	  where supplier_id = @currentSupplierID

	  if (@currentSupplierID = @supplier_id) begin
	     select @productValue = price * amount
		 from Products
		 where id = @product_id
		 and supplier_id = @supplier_id

		 if(@productValue = @maxProductValue) begin
		    set @x = 1
			break;
		 end

	  end
	  fetch next from cs_supplier into @currentSupplierID
   end
   close cs_supplier
   deallocate cs_supplier

   return @x
   
end

select dbo.inspect_product_highest_value(12,9)


-- con tro in ra thong tin cac san pham cua 1 nha cung cap
declare cs_supplier_product scroll cursor
for select 
		   Suppliers.id,
		   Suppliers.name,
		   Suppliers.email,
		   Suppliers.phone_number,
		   Products.id,
           Products.name,
		   Products.price,
		   Products.amount,
		   Products.category	   
		   
from Products, Suppliers
where Products.supplier_id = Suppliers.id
open cs_supplier_product

DECLARE @SupplierID INT,
        @SupplierName NVARCHAR(100),
        @SupplierEmail NVARCHAR(100),
        @SupplierPhone NVARCHAR(15),
        @ProductID INT,
        @ProductName NVARCHAR(100),
        @ProductPrice DECIMAL(18, 2),
        @ProductAmount INT,
        @ProductCategory NVARCHAR(50);

FETCH NEXT FROM cs_supplier_product INTO @SupplierID, @SupplierName, @SupplierEmail, 
                                         @SupplierPhone, @ProductID, @ProductName, 
                                         @ProductPrice, @ProductAmount, @ProductCategory;
while(@@FETCH_STATUS = 0) begin
   PRINT 'Supplier ID: ' + CAST(@SupplierID AS NVARCHAR) + ', Name: ' + @SupplierName +
          ', Email: ' + ISNULL(@SupplierEmail, 'N/A') + ', Phone: ' + ISNULL(@SupplierPhone, 'N/A');

    IF @ProductID IS NOT NULL
    BEGIN
        PRINT '   Product ID: ' + CAST(@ProductID AS NVARCHAR) + ', Name: ' + @ProductName +
              ', Price: ' + CAST(@ProductPrice AS NVARCHAR) + ', Amount: ' + CAST(@ProductAmount AS NVARCHAR) +
              ', Category: ' + ISNULL(@ProductCategory, 'N/A');
    END
    ELSE
    BEGIN
        PRINT ' khong co san pham nao';
    END
  
    fetch next from cs_supplier_product into @SupplierID, @SupplierName, @SupplierEmail, 
                                             @SupplierPhone, @ProductID, @ProductName, 
                                             @ProductPrice, @ProductAmount, @ProductCategory;
end

close cs_supplier_product
deallocate cs_supplier_product



-- trigger rut toan bo san pham cua nha cung cap dung hop tac
create trigger delete_suppliers
on Products
for delete
as begin
   update Products
   set amount = 0, buy_price = 0, price = 0
   where supplier_id in (select id from deleted)  
end

