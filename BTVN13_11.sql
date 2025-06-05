create database QLSV

use QLSV

go

create table Khoa (
	MaKh nvarchar(250) not null Primary Key,
	VPKh nvarchar(250)
)

go 

create table Lop (
	MaLop nvarchar(250) not null Primary key,
	MaKh nvarchar(250) Foreign key references Khoa(MaKh)
)

go 

create table SinhVien (
	MaSV nvarchar(250) not null Primary key,
	HoSV nvarchar(250),
	TenSV nvarchar(250),
	NSSV int,
	DCSV nvarchar(250),
	LopTr bit,
	MaLop nvarchar(250) Foreign key references Lop(MaLop)
)

go

create table MonHoc (
	MaMH nvarchar(250) not null Primary key,
	TenMH nvarchar(250),
	LT int,
	TH int
)

go

create table CTHoc (
	MaLop nvarchar(250) Foreign key references Lop(MaLop),
	HK int,
	MaMH nvarchar(250) Foreign key references MonHoc(MaMH)
)

create table DiemSV (
	MaSV nvarchar(250) Foreign key references SinhVien(MaSV),
	MaMH nvarchar(250) Foreign key references MonHoc(MaMH),
	Lan int,
	Diem float
)

--1 
select * from Lop

--2
select * from SinhVien
where MaLop = N'TH1'

--3
select * 
from SinhVien JOIN Lop ON SinhVien.MaLop = Lop.MaLop
where MaKh = N'CNTT'

--4
select *
from CTHoc JOIN MonHoc ON CTHoc.MaMH = MonHoc.MaMH
where CTHoc.MaLop = N'TH1'

--5
select *
from DiemSV JOIN SinhVien ON DiemSV.MaSV = SinhVien.MaSV JOIN MonHoc ON DiemSV.MaMH = MonHoc.MaMH
where TenMH = N'CSDL' and MaLop = N'TH1' and Lan = 1

--6
select AVG(Diem) as [Điểm trung bình môn CSDL]
from DiemSV JOIN SinhVien ON DiemSV.MaSV = SinhVien.MaSV JOIN MonHoc ON DiemSV.MaMH = MonHoc.MaMH
where TenMH = N'CSDL' and MaLop = N'TH1' and Lan = 1

--7
select count(MaSV) as [Số lượng Sinh Viên]
from SinhVien 
where MaLop = N'TH2'

--8
select count(MaMH) as [Số lượng môn học]
from CTHoc
where (HK = 1 or HK = 2) and MaLop = N'TH1'

--9
select top 3 *
from SinhVien JOIN DiemSV ON SinhVien.MaSV = DiemSV.MaSV
order by Diem DESC

--10
select MaLop, count(MaSV) as [Sĩ Số]
from SinhVien
group by MaLop

--11
select Top 1 Khoa.MaKh, count (MaSV) as [Số Lượng Sinh Viên]
from SinhVien JOIN Lop ON SinhVien.MaLop = Lop.MaLop JOIN Khoa ON Lop.MaKh = Khoa.MaKh
group by Khoa.MaKh
order by count(MaSV) DESC

--12
select Top 1 Lop.MaLop, count(MaSV) as [Số Lượng Sinh Viên]
from SinhVien JOIN Lop ON SinhVien.MaLop = Lop.MaLop JOIN Khoa ON Lop.MaKh = Khoa.MaKh
where Khoa.MaKh = N'CNTT'
group by Lop.MaLop
order by count(MaSV) DESC

--13
select Top 1 MonHoc.MaMH, count(MaSV) as [Số lượng sinh viên không đạt]
from MonHoc JOIN DiemSV ON MonHoc.MaMH = DiemSV.MaMH
where Diem < 4 and Lan = 1
group by MonHoc.MaMH
order by count(MaSV) DESC


--14
select DiemSV.MaSV, MonHoc.MaMH, max(Diem) as [Điểm số lớn nhất]
from DiemSV JOIN SinhVien ON DiemSV.MaSV = SinhVien.MaSV JOIN MonHoc ON MonHoc.MaMH = DiemSV.MaMH
group by DiemSV.MaSV, MonHoc.MaMH

--15
select SinhVien.MaLop, avg(Diem) as [Điểm trung bình môn CSDL]
from DiemSV JOIN SinhVien ON DiemSV.MaSV = SinhVien.MaSV JOIN Lop ON SinhVien.MaLop = Lop.MaLop JOIN MonHoc ON DiemSV.MaMH = MonHoc.MaMH
where Lop.MaKh = N'CNTT' and Lan = 1 and TenMH = N'CSDL'
group by SinhVien.MaLop

--16
select SinhVien.MaSV
from SinhVien JOIN DiemSV ON SinhVien.MaSV = DiemSV.MaSV JOIN CTHoc ON CTHoc.MaMH = DiemSV.MaMH
where SinhVien.MaLop = N'TH1' and Lan = 1 and HK = 2 and Diem > 4
group by SinhVien.MaSV
having COUNT(DiemSV.MaMH) = (select COUNT(MaMH) from CTHoc where MaLop = N'TH1' and HK = 2) 

--
ALTER TABLE SINHVIEN
ADD DEFAULT 'BACH' FOR TENSV