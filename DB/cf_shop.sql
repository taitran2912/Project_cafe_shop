-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th10 22, 2025 lúc 06:43 PM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `cf_shop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Account`
--

CREATE TABLE `Account` (
  `ID` int(10) NOT NULL,
  `Usename` varchar(50) NOT NULL,
  `Name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `Phone` varchar(11) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `Role` int(10) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Account`
--

INSERT INTO `Account` (`ID`, `Usename`, `Name`, `Password`, `Phone`, `Email`, `Role`, `Status`) VALUES
(1, 'VanA', 'Nguyễn Văn A', '123456', '0901000001', 'vana@gmail.com', 3, 'active'),
(2, 'ThiB', 'Trần Thị B', '123456', '0902000002', 'thib@gmail.com', 3, 'active'),
(3, 'VanC', 'Lê Văn C', '123456', '0903000003', 'vanc@gmail.com', 3, 'active'),
(4, 'ThiD', 'Phạm Thị D', '123456', '0904000004', 'thid@gmail.com', 3, 'active'),
(5, 'VanE', 'Đỗ Văn E', '123456', '0905000005', 'vane@gmail.com', 3, 'active');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Branches`
--

CREATE TABLE `Branches` (
  `ID` int(10) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Address` varchar(1000) DEFAULT NULL,
  `Phone` varchar(11) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Branches`
--

INSERT INTO `Branches` (`ID`, `Name`, `Address`, `Phone`, `Status`) VALUES
(1, 'Chi nhánh Quận 1', '123 Nguyễn Huệ, Quận 1, TP.HCM', '02838250001', 'active'),
(2, 'Chi nhánh Quận 3', '45 Cách Mạng Tháng 8, Quận 3, TP.HCM', '02838250003', 'active'),
(3, 'Chi nhánh Quận 7', '88 Nguyễn Thị Thập, Quận 7, TP.HCM', '02838250007', 'active'),
(4, 'Chi nhánh Bình Thạnh', '12 Phan Đăng Lưu, Bình Thạnh, TP.HCM', '02838250005', 'active'),
(5, 'Chi nhánh Thủ Đức', '56 Võ Văn Ngân, Thủ Đức, TP.HCM', '02838250009', 'active');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Cart`
--

CREATE TABLE `Cart` (
  `ID` int(11) NOT NULL,
  `ID_Customer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Cart`
--

INSERT INTO `Cart` (`ID`, `ID_Customer`) VALUES
(2, 1),
(4, 3),
(3, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Cart_detail`
--

CREATE TABLE `Cart_detail` (
  `ID_Cart` int(11) NOT NULL,
  `ID_Product` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Cart_detail`
--

INSERT INTO `Cart_detail` (`ID_Cart`, `ID_Product`, `Quantity`) VALUES
(2, 2, 4),
(2, 9, 1),
(2, 3, 1),
(4, 2, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Categories`
--

CREATE TABLE `Categories` (
  `ID` int(10) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Categories`
--

INSERT INTO `Categories` (`ID`, `Name`, `Status`) VALUES
(1, 'Cà phê', 'active'),
(2, 'Trà', 'active'),
(3, 'Sinh tố và Nước ép', 'active'),
(4, 'Đá xay', 'active'),
(5, 'Nước đóng chai', 'active'),
(6, 'test', 'deactive');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Coupons`
--

CREATE TABLE `Coupons` (
  `ID` int(10) NOT NULL,
  `Code` varchar(10) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Discount_value` decimal(10,0) DEFAULT NULL,
  `Start` time DEFAULT NULL,
  `End` time DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL,
  `Quantity` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Coupon_usage`
--

CREATE TABLE `Coupon_usage` (
  `ID` int(10) NOT NULL,
  `ID_coupon` int(10) DEFAULT NULL,
  `ID_customer` int(10) DEFAULT NULL,
  `ID_order` int(10) DEFAULT NULL,
  `Used_at` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Customer_Profile`
--

CREATE TABLE `Customer_Profile` (
  `ID` int(10) NOT NULL,
  `ID_account` int(10) DEFAULT NULL,
  `Points` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Customer_Profile`
--

INSERT INTO `Customer_Profile` (`ID`, `ID_account`, `Points`) VALUES
(1, 1, 20),
(2, 2, 35),
(3, 3, 15),
(4, 4, 50),
(5, 5, 25);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Inventory`
--

CREATE TABLE `Inventory` (
  `ID` int(10) NOT NULL,
  `ID_Material` int(10) DEFAULT NULL,
  `ID_Brach` int(10) DEFAULT NULL,
  `Quantity` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Material`
--

CREATE TABLE `Material` (
  `ID` int(10) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Unit` varchar(50) DEFAULT NULL,
  `Update_at` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Material`
--

INSERT INTO `Material` (`ID`, `Name`, `Unit`, `Update_at`) VALUES
(1, 'Cà phê bột', 'gram', '02:31:58'),
(2, 'Sữa đặc', 'ml', '02:31:58'),
(3, 'Sữa tươi', 'ml', '02:31:58'),
(4, 'Đường', 'gram', '02:31:58'),
(5, 'Trà đen', 'gram', '02:31:58'),
(6, 'Trà xanh', 'gram', '02:31:58'),
(7, 'Đào ngâm', 'miếng', '02:31:58'),
(8, 'Cam tươi', 'trái', '02:31:58'),
(9, 'Dưa hấu', 'gram', '02:31:58'),
(10, 'Bơ', 'gram', '02:31:58'),
(11, 'Xoài', 'gram', '02:31:58'),
(12, 'Đá viên', 'gram', '02:31:58'),
(13, 'Nước suối Aquafina', 'chai', '02:31:58'),
(14, 'Coca-Cola', 'chai', '02:31:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Orders`
--

CREATE TABLE `Orders` (
  `ID` int(10) NOT NULL,
  `ID_customer` int(10) DEFAULT NULL,
  `ID_branch` int(10) DEFAULT NULL,
  `ID_table` int(10) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL,
  `Time` time DEFAULT NULL,
  `Address` varchar(100) NOT NULL,
  `Payment_status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Orders`
--

INSERT INTO `Orders` (`ID`, `ID_customer`, `ID_branch`, `ID_table`, `Status`, `Time`, `Address`, `Payment_status`) VALUES
(1, 1, 1, NULL, 'completed', '09:15:00', '', 'paid'),
(2, 2, 2, NULL, 'completed', '10:30:00', '', 'paid'),
(3, 3, 3, NULL, 'completed', '14:20:00', '', 'paid'),
(4, 4, 4, NULL, 'completed', '16:45:00', '', 'paid'),
(5, 5, 5, NULL, 'completed', '18:10:00', '', 'paid');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Order_detail`
--

CREATE TABLE `Order_detail` (
  `ID` int(10) NOT NULL,
  `ID_order` int(10) DEFAULT NULL,
  `ID_product` int(10) DEFAULT NULL,
  `Quantity` int(10) DEFAULT NULL,
  `Price` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Order_detail`
--

INSERT INTO `Order_detail` (`ID`, `ID_order`, `ID_product`, `Quantity`, `Price`) VALUES
(6, 1, 1, 2, 25000),
(7, 1, 2, 1, 28000),
(8, 1, 4, 1, 35000),
(9, 1, 7, 1, 45000),
(10, 1, 11, 2, 55000),
(11, 2, 3, 1, 30000),
(12, 2, 5, 2, 25000),
(13, 2, 6, 1, 40000),
(14, 2, 9, 1, 38000),
(15, 2, 14, 2, 15000),
(16, 3, 1, 1, 25000),
(17, 3, 2, 2, 28000),
(18, 3, 8, 1, 40000),
(19, 3, 10, 1, 35000),
(20, 3, 13, 1, 55000),
(21, 4, 5, 2, 25000),
(22, 4, 6, 1, 40000),
(23, 4, 9, 1, 38000),
(24, 4, 12, 1, 55000),
(25, 4, 14, 1, 15000),
(26, 5, 3, 1, 30000),
(27, 5, 7, 1, 45000),
(28, 5, 8, 2, 40000),
(29, 5, 11, 1, 55000),
(30, 5, 15, 1, 18000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Payment`
--

CREATE TABLE `Payment` (
  `ID` int(10) NOT NULL,
  `ID_order` int(10) DEFAULT NULL,
  `Amount` decimal(19,0) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `Paid_at` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Payment`
--

INSERT INTO `Payment` (`ID`, `ID_order`, `Amount`, `method`, `Paid_at`) VALUES
(1, 1, 243000, 'cash', '09:20:00'),
(2, 2, 168000, 'momo', '10:35:00'),
(3, 3, 211000, 'cash', '14:25:00'),
(4, 4, 183000, 'banking', '16:50:00'),
(5, 5, 188000, 'cash', '18:15:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Product`
--

CREATE TABLE `Product` (
  `ID` int(10) NOT NULL,
  `ID_category` int(10) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Price` decimal(10,0) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL,
  `Image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Product`
--

INSERT INTO `Product` (`ID`, `ID_category`, `Name`, `Description`, `Price`, `Status`, `Image`) VALUES
(1, 1, 'Cà phê đen đá', 'Cà phê nguyên chất pha phin, vị đậm đà truyền thống Việt Nam', 25000, 'active', 'americano-coffee-black.jpg'),
(2, 1, 'Cà phê sữa đá', 'Cà phê phin kết hợp sữa đặc, hương vị béo ngọt', 28000, 'active', 'cappuccino-with-latte-art.jpg'),
(3, 1, 'Bạc xỉu', 'Cà phê sữa tỉ lệ nhiều sữa, dễ uống, phù hợp mọi lứa tuổi', 30000, 'active', 'vanilla-latte-coffee.jpg'),
(4, 2, 'Trà đào cam sả', 'Trà đen hòa quyện vị đào, cam và sả tươi mát', 35000, 'active', 'peach-tea-with-herbs.jpg'),
(5, 2, 'Trà chanh tươi', 'Trà xanh pha chanh tươi thanh mát, giải nhiệt nhanh', 25000, 'active', 'earl-grey-tea-cup.jpg'),
(6, 2, 'Trà sữa truyền thống', 'Trà đen kết hợp sữa béo, thêm trân châu dai ngon', 40000, 'active', 'green-tea-in-elegant-cup.jpg'),
(7, 3, 'Sinh tố bơ', 'Bơ tươi xay cùng sữa đặc, vị béo ngậy thơm ngon', 45000, 'active', 'sinhtobo.png'),
(8, 3, 'Sinh tố xoài', 'Xoài chín xay cùng đá, vị ngọt tự nhiên dễ uống', 40000, 'active', 'sinhtoxoai.png'),
(9, 3, 'Nước ép cam', 'Cam tươi vắt nguyên chất, giữ trọn vitamin C', 38000, 'active', 'nuocepcam.png'),
(10, 3, 'Nước ép dưa hấu', 'Dưa hấu ép tươi, vị ngọt mát và giải khát nhanh', 35000, 'active', 'nuocepduahau.png'),
(11, 4, 'Cà phê đá xay', 'Cà phê xay cùng đá và kem tươi, vị đậm lạnh sảng khoái', 55000, 'active', 'iced-coffee-with-milk-foam.jpg'),
(12, 4, 'Matcha đá xay', 'Bột trà xanh Nhật Bản kết hợp sữa tươi và đá xay', 55000, 'active', 'iced-matcha-latte.png'),
(13, 4, 'Chocolate đá xay', 'Sô cô la nguyên chất pha lạnh cùng kem béo mịn', 55000, 'active', 'mocha-chocolate-coffee.jpg'),
(14, 5, 'Nước suối Aquafina', 'Nước tinh khiết đóng chai 500ml', 15000, 'active', 'Aquafina.png'),
(15, 5, 'Coca-Cola', 'Nước giải khát có gas, chai 390ml', 18000, 'active', 'coca.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Product_detail`
--

CREATE TABLE `Product_detail` (
  `ID` int(10) NOT NULL,
  `ID_product` int(10) DEFAULT NULL,
  `ID_material` int(10) DEFAULT NULL,
  `Quantity` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Product_detail`
--

INSERT INTO `Product_detail` (`ID`, `ID_product`, `ID_material`, `Quantity`) VALUES
(1, 1, 1, 25),
(2, 1, 4, 10),
(3, 1, 12, 150),
(4, 2, 1, 20),
(5, 2, 2, 30),
(6, 2, 4, 5),
(7, 2, 12, 150),
(8, 3, 1, 10),
(9, 3, 2, 40),
(10, 3, 3, 50),
(11, 3, 12, 120),
(12, 4, 5, 10),
(13, 4, 7, 2),
(14, 4, 8, 1),
(15, 4, 4, 5),
(16, 4, 12, 150),
(17, 5, 6, 10),
(18, 5, 8, 1),
(19, 5, 4, 5),
(20, 5, 12, 150),
(21, 6, 5, 10),
(22, 6, 3, 50),
(23, 6, 2, 20),
(24, 6, 4, 5),
(25, 6, 12, 150),
(26, 7, 10, 150),
(27, 7, 2, 30),
(28, 7, 3, 50),
(29, 7, 12, 100),
(30, 8, 11, 150),
(31, 8, 2, 20),
(32, 8, 3, 50),
(33, 8, 12, 100),
(34, 9, 8, 1),
(35, 9, 4, 5),
(36, 9, 12, 100),
(37, 10, 9, 150),
(38, 10, 4, 5),
(39, 10, 12, 100),
(40, 11, 1, 20),
(41, 11, 3, 80),
(42, 11, 2, 30),
(43, 11, 12, 200),
(44, 12, 6, 15),
(45, 12, 3, 100),
(46, 12, 2, 20),
(47, 12, 12, 200),
(48, 13, 3, 100),
(49, 13, 2, 30),
(50, 13, 12, 200),
(51, 14, 13, 1),
(52, 15, 14, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Reviews`
--

CREATE TABLE `Reviews` (
  `ID` int(10) NOT NULL,
  `ID_order` int(10) DEFAULT NULL,
  `Rating` int(10) DEFAULT NULL,
  `Comment` text DEFAULT NULL,
  `Reply` text DEFAULT NULL,
  `Create_at` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Staff`
--

CREATE TABLE `Staff` (
  `ID` int(10) NOT NULL,
  `ID_account` int(10) DEFAULT NULL,
  `ID_brand` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Table_Coffee`
--

CREATE TABLE `Table_Coffee` (
  `ID` int(10) NOT NULL,
  `ID_Brach` int(10) DEFAULT NULL,
  `No` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `Account`
--
ALTER TABLE `Account`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `Branches`
--
ALTER TABLE `Branches`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `Cart`
--
ALTER TABLE `Cart`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `cart_ibfk_1` (`ID_Customer`);

--
-- Chỉ mục cho bảng `Cart_detail`
--
ALTER TABLE `Cart_detail`
  ADD KEY `ID_Cart` (`ID_Cart`),
  ADD KEY `ID_Product` (`ID_Product`);

--
-- Chỉ mục cho bảng `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `Coupons`
--
ALTER TABLE `Coupons`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `Coupon_usage`
--
ALTER TABLE `Coupon_usage`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_coupon` (`ID_coupon`),
  ADD KEY `ID_customer` (`ID_customer`);

--
-- Chỉ mục cho bảng `Customer_Profile`
--
ALTER TABLE `Customer_Profile`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_account` (`ID_account`);

--
-- Chỉ mục cho bảng `Inventory`
--
ALTER TABLE `Inventory`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_Material` (`ID_Material`),
  ADD KEY `ID_Brach` (`ID_Brach`);

--
-- Chỉ mục cho bảng `Material`
--
ALTER TABLE `Material`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_customer` (`ID_customer`),
  ADD KEY `ID_branch` (`ID_branch`),
  ADD KEY `ID_table` (`ID_table`);

--
-- Chỉ mục cho bảng `Order_detail`
--
ALTER TABLE `Order_detail`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_order` (`ID_order`);

--
-- Chỉ mục cho bảng `Payment`
--
ALTER TABLE `Payment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_order` (`ID_order`);

--
-- Chỉ mục cho bảng `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_category` (`ID_category`);

--
-- Chỉ mục cho bảng `Product_detail`
--
ALTER TABLE `Product_detail`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_product` (`ID_product`);

--
-- Chỉ mục cho bảng `Reviews`
--
ALTER TABLE `Reviews`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_order` (`ID_order`);

--
-- Chỉ mục cho bảng `Staff`
--
ALTER TABLE `Staff`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_account` (`ID_account`),
  ADD KEY `ID_brand` (`ID_brand`);

--
-- Chỉ mục cho bảng `Table_Coffee`
--
ALTER TABLE `Table_Coffee`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_Brach` (`ID_Brach`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `Account`
--
ALTER TABLE `Account`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `Branches`
--
ALTER TABLE `Branches`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `Cart`
--
ALTER TABLE `Cart`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `Categories`
--
ALTER TABLE `Categories`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `Coupons`
--
ALTER TABLE `Coupons`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Coupon_usage`
--
ALTER TABLE `Coupon_usage`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Customer_Profile`
--
ALTER TABLE `Customer_Profile`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `Inventory`
--
ALTER TABLE `Inventory`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Material`
--
ALTER TABLE `Material`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `Orders`
--
ALTER TABLE `Orders`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `Order_detail`
--
ALTER TABLE `Order_detail`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT cho bảng `Payment`
--
ALTER TABLE `Payment`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `Product`
--
ALTER TABLE `Product`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `Product_detail`
--
ALTER TABLE `Product_detail`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT cho bảng `Reviews`
--
ALTER TABLE `Reviews`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Staff`
--
ALTER TABLE `Staff`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Table_Coffee`
--
ALTER TABLE `Table_Coffee`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `Cart`
--
ALTER TABLE `Cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`ID_Customer`) REFERENCES `Account` (`ID`);

--
-- Các ràng buộc cho bảng `Cart_detail`
--
ALTER TABLE `Cart_detail`
  ADD CONSTRAINT `cart_detail_ibfk_1` FOREIGN KEY (`ID_Cart`) REFERENCES `Cart` (`ID`),
  ADD CONSTRAINT `cart_detail_ibfk_2` FOREIGN KEY (`ID_Product`) REFERENCES `Product` (`ID`);

--
-- Các ràng buộc cho bảng `Coupon_usage`
--
ALTER TABLE `Coupon_usage`
  ADD CONSTRAINT `coupon_usage_ibfk_1` FOREIGN KEY (`ID_coupon`) REFERENCES `Coupons` (`ID`),
  ADD CONSTRAINT `coupon_usage_ibfk_2` FOREIGN KEY (`ID_customer`) REFERENCES `Customer_Profile` (`ID`);

--
-- Các ràng buộc cho bảng `Customer_Profile`
--
ALTER TABLE `Customer_Profile`
  ADD CONSTRAINT `customer_profile_ibfk_1` FOREIGN KEY (`ID_account`) REFERENCES `Account` (`ID`);

--
-- Các ràng buộc cho bảng `Inventory`
--
ALTER TABLE `Inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`ID_Material`) REFERENCES `Material` (`ID`),
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`ID_Brach`) REFERENCES `Branches` (`ID`);

--
-- Các ràng buộc cho bảng `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`ID_customer`) REFERENCES `Customer_Profile` (`ID`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`ID_branch`) REFERENCES `Branches` (`ID`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`ID_table`) REFERENCES `Table_Coffee` (`ID`);

--
-- Các ràng buộc cho bảng `Order_detail`
--
ALTER TABLE `Order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`ID_order`) REFERENCES `orders` (`ID`);

--
-- Các ràng buộc cho bảng `Payment`
--
ALTER TABLE `Payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`ID_order`) REFERENCES `orders` (`ID`);

--
-- Các ràng buộc cho bảng `Product`
--
ALTER TABLE `Product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`ID_category`) REFERENCES `Categories` (`ID`);

--
-- Các ràng buộc cho bảng `Product_detail`
--
ALTER TABLE `Product_detail`
  ADD CONSTRAINT `product_detail_ibfk_1` FOREIGN KEY (`ID_product`) REFERENCES `Product` (`ID`);

--
-- Các ràng buộc cho bảng `Reviews`
--
ALTER TABLE `Reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`ID_order`) REFERENCES `orders` (`ID`);

--
-- Các ràng buộc cho bảng `Staff`
--
ALTER TABLE `Staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`ID_account`) REFERENCES `Account` (`ID`),
  ADD CONSTRAINT `staff_ibfk_2` FOREIGN KEY (`ID_brand`) REFERENCES `Branches` (`ID`);

--
-- Các ràng buộc cho bảng `Table_Coffee`
--
ALTER TABLE `Table_Coffee`
  ADD CONSTRAINT `table_coffee_ibfk_1` FOREIGN KEY (`ID_Brach`) REFERENCES `Branches` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th10 22, 2025 lúc 06:43 PM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `cf_shop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Account`
--

CREATE TABLE `Account` (
  `ID` int(10) NOT NULL,
  `Usename` varchar(50) NOT NULL,
  `Name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `Phone` varchar(11) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `Role` int(10) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Account`
--

INSERT INTO `Account` (`ID`, `Usename`, `Name`, `Password`, `Phone`, `Email`, `Role`, `Status`) VALUES
(1, 'VanA', 'Nguyễn Văn A', '123456', '0901000001', 'vana@gmail.com', 3, 'active'),
(2, 'ThiB', 'Trần Thị B', '123456', '0902000002', 'thib@gmail.com', 3, 'active'),
(3, 'VanC', 'Lê Văn C', '123456', '0903000003', 'vanc@gmail.com', 3, 'active'),
(4, 'ThiD', 'Phạm Thị D', '123456', '0904000004', 'thid@gmail.com', 3, 'active'),
(5, 'VanE', 'Đỗ Văn E', '123456', '0905000005', 'vane@gmail.com', 3, 'active');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Branches`
--

CREATE TABLE `Branches` (
  `ID` int(10) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Address` varchar(1000) DEFAULT NULL,
  `Phone` varchar(11) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Branches`
--

INSERT INTO `Branches` (`ID`, `Name`, `Address`, `Phone`, `Status`) VALUES
(1, 'Chi nhánh Quận 1', '123 Nguyễn Huệ, Quận 1, TP.HCM', '02838250001', 'active'),
(2, 'Chi nhánh Quận 3', '45 Cách Mạng Tháng 8, Quận 3, TP.HCM', '02838250003', 'active'),
(3, 'Chi nhánh Quận 7', '88 Nguyễn Thị Thập, Quận 7, TP.HCM', '02838250007', 'active'),
(4, 'Chi nhánh Bình Thạnh', '12 Phan Đăng Lưu, Bình Thạnh, TP.HCM', '02838250005', 'active'),
(5, 'Chi nhánh Thủ Đức', '56 Võ Văn Ngân, Thủ Đức, TP.HCM', '02838250009', 'active');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Cart`
--

CREATE TABLE `Cart` (
  `ID` int(11) NOT NULL,
  `ID_Customer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Cart`
--

INSERT INTO `Cart` (`ID`, `ID_Customer`) VALUES
(2, 1),
(4, 3),
(3, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Cart_detail`
--

CREATE TABLE `Cart_detail` (
  `ID_Cart` int(11) NOT NULL,
  `ID_Product` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Cart_detail`
--

INSERT INTO `Cart_detail` (`ID_Cart`, `ID_Product`, `Quantity`) VALUES
(2, 2, 4),
(2, 9, 1),
(2, 3, 1),
(4, 2, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Categories`
--

CREATE TABLE `Categories` (
  `ID` int(10) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Categories`
--

INSERT INTO `Categories` (`ID`, `Name`, `Status`) VALUES
(1, 'Cà phê', 'active'),
(2, 'Trà', 'active'),
(3, 'Sinh tố và Nước ép', 'active'),
(4, 'Đá xay', 'active'),
(5, 'Nước đóng chai', 'active'),
(6, 'test', 'deactive');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Coupons`
--

CREATE TABLE `Coupons` (
  `ID` int(10) NOT NULL,
  `Code` varchar(10) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Discount_value` decimal(10,0) DEFAULT NULL,
  `Start` time DEFAULT NULL,
  `End` time DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL,
  `Quantity` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Coupon_usage`
--

CREATE TABLE `Coupon_usage` (
  `ID` int(10) NOT NULL,
  `ID_coupon` int(10) DEFAULT NULL,
  `ID_customer` int(10) DEFAULT NULL,
  `ID_order` int(10) DEFAULT NULL,
  `Used_at` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Customer_Profile`
--

CREATE TABLE `Customer_Profile` (
  `ID` int(10) NOT NULL,
  `ID_account` int(10) DEFAULT NULL,
  `Points` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Customer_Profile`
--

INSERT INTO `Customer_Profile` (`ID`, `ID_account`, `Points`) VALUES
(1, 1, 20),
(2, 2, 35),
(3, 3, 15),
(4, 4, 50),
(5, 5, 25);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Inventory`
--

CREATE TABLE `Inventory` (
  `ID` int(10) NOT NULL,
  `ID_Material` int(10) DEFAULT NULL,
  `ID_Brach` int(10) DEFAULT NULL,
  `Quantity` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Material`
--

CREATE TABLE `Material` (
  `ID` int(10) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Unit` varchar(50) DEFAULT NULL,
  `Update_at` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Material`
--

INSERT INTO `Material` (`ID`, `Name`, `Unit`, `Update_at`) VALUES
(1, 'Cà phê bột', 'gram', '02:31:58'),
(2, 'Sữa đặc', 'ml', '02:31:58'),
(3, 'Sữa tươi', 'ml', '02:31:58'),
(4, 'Đường', 'gram', '02:31:58'),
(5, 'Trà đen', 'gram', '02:31:58'),
(6, 'Trà xanh', 'gram', '02:31:58'),
(7, 'Đào ngâm', 'miếng', '02:31:58'),
(8, 'Cam tươi', 'trái', '02:31:58'),
(9, 'Dưa hấu', 'gram', '02:31:58'),
(10, 'Bơ', 'gram', '02:31:58'),
(11, 'Xoài', 'gram', '02:31:58'),
(12, 'Đá viên', 'gram', '02:31:58'),
(13, 'Nước suối Aquafina', 'chai', '02:31:58'),
(14, 'Coca-Cola', 'chai', '02:31:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Orders`
--

CREATE TABLE `Orders` (
  `ID` int(10) NOT NULL,
  `ID_customer` int(10) DEFAULT NULL,
  `ID_branch` int(10) DEFAULT NULL,
  `ID_table` int(10) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL,
  `Time` time DEFAULT NULL,
  `Address` varchar(100) NOT NULL,
  `Payment_status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Orders`
--

INSERT INTO `Orders` (`ID`, `ID_customer`, `ID_branch`, `ID_table`, `Status`, `Time`, `Address`, `Payment_status`) VALUES
(1, 1, 1, NULL, 'completed', '09:15:00', '', 'paid'),
(2, 2, 2, NULL, 'completed', '10:30:00', '', 'paid'),
(3, 3, 3, NULL, 'completed', '14:20:00', '', 'paid'),
(4, 4, 4, NULL, 'completed', '16:45:00', '', 'paid'),
(5, 5, 5, NULL, 'completed', '18:10:00', '', 'paid');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Order_detail`
--

CREATE TABLE `Order_detail` (
  `ID` int(10) NOT NULL,
  `ID_order` int(10) DEFAULT NULL,
  `ID_product` int(10) DEFAULT NULL,
  `Quantity` int(10) DEFAULT NULL,
  `Price` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Order_detail`
--

INSERT INTO `Order_detail` (`ID`, `ID_order`, `ID_product`, `Quantity`, `Price`) VALUES
(6, 1, 1, 2, 25000),
(7, 1, 2, 1, 28000),
(8, 1, 4, 1, 35000),
(9, 1, 7, 1, 45000),
(10, 1, 11, 2, 55000),
(11, 2, 3, 1, 30000),
(12, 2, 5, 2, 25000),
(13, 2, 6, 1, 40000),
(14, 2, 9, 1, 38000),
(15, 2, 14, 2, 15000),
(16, 3, 1, 1, 25000),
(17, 3, 2, 2, 28000),
(18, 3, 8, 1, 40000),
(19, 3, 10, 1, 35000),
(20, 3, 13, 1, 55000),
(21, 4, 5, 2, 25000),
(22, 4, 6, 1, 40000),
(23, 4, 9, 1, 38000),
(24, 4, 12, 1, 55000),
(25, 4, 14, 1, 15000),
(26, 5, 3, 1, 30000),
(27, 5, 7, 1, 45000),
(28, 5, 8, 2, 40000),
(29, 5, 11, 1, 55000),
(30, 5, 15, 1, 18000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Payment`
--

CREATE TABLE `Payment` (
  `ID` int(10) NOT NULL,
  `ID_order` int(10) DEFAULT NULL,
  `Amount` decimal(19,0) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `Paid_at` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Payment`
--

INSERT INTO `Payment` (`ID`, `ID_order`, `Amount`, `method`, `Paid_at`) VALUES
(1, 1, 243000, 'cash', '09:20:00'),
(2, 2, 168000, 'momo', '10:35:00'),
(3, 3, 211000, 'cash', '14:25:00'),
(4, 4, 183000, 'banking', '16:50:00'),
(5, 5, 188000, 'cash', '18:15:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Product`
--

CREATE TABLE `Product` (
  `ID` int(10) NOT NULL,
  `ID_category` int(10) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Price` decimal(10,0) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL,
  `Image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Product`
--

INSERT INTO `Product` (`ID`, `ID_category`, `Name`, `Description`, `Price`, `Status`, `Image`) VALUES
(1, 1, 'Cà phê đen đá', 'Cà phê nguyên chất pha phin, vị đậm đà truyền thống Việt Nam', 25000, 'active', 'americano-coffee-black.jpg'),
(2, 1, 'Cà phê sữa đá', 'Cà phê phin kết hợp sữa đặc, hương vị béo ngọt', 28000, 'active', 'cappuccino-with-latte-art.jpg'),
(3, 1, 'Bạc xỉu', 'Cà phê sữa tỉ lệ nhiều sữa, dễ uống, phù hợp mọi lứa tuổi', 30000, 'active', 'vanilla-latte-coffee.jpg'),
(4, 2, 'Trà đào cam sả', 'Trà đen hòa quyện vị đào, cam và sả tươi mát', 35000, 'active', 'peach-tea-with-herbs.jpg'),
(5, 2, 'Trà chanh tươi', 'Trà xanh pha chanh tươi thanh mát, giải nhiệt nhanh', 25000, 'active', 'earl-grey-tea-cup.jpg'),
(6, 2, 'Trà sữa truyền thống', 'Trà đen kết hợp sữa béo, thêm trân châu dai ngon', 40000, 'active', 'green-tea-in-elegant-cup.jpg'),
(7, 3, 'Sinh tố bơ', 'Bơ tươi xay cùng sữa đặc, vị béo ngậy thơm ngon', 45000, 'active', 'sinhtobo.png'),
(8, 3, 'Sinh tố xoài', 'Xoài chín xay cùng đá, vị ngọt tự nhiên dễ uống', 40000, 'active', 'sinhtoxoai.png'),
(9, 3, 'Nước ép cam', 'Cam tươi vắt nguyên chất, giữ trọn vitamin C', 38000, 'active', 'nuocepcam.png'),
(10, 3, 'Nước ép dưa hấu', 'Dưa hấu ép tươi, vị ngọt mát và giải khát nhanh', 35000, 'active', 'nuocepduahau.png'),
(11, 4, 'Cà phê đá xay', 'Cà phê xay cùng đá và kem tươi, vị đậm lạnh sảng khoái', 55000, 'active', 'iced-coffee-with-milk-foam.jpg'),
(12, 4, 'Matcha đá xay', 'Bột trà xanh Nhật Bản kết hợp sữa tươi và đá xay', 55000, 'active', 'iced-matcha-latte.png'),
(13, 4, 'Chocolate đá xay', 'Sô cô la nguyên chất pha lạnh cùng kem béo mịn', 55000, 'active', 'mocha-chocolate-coffee.jpg'),
(14, 5, 'Nước suối Aquafina', 'Nước tinh khiết đóng chai 500ml', 15000, 'active', 'Aquafina.png'),
(15, 5, 'Coca-Cola', 'Nước giải khát có gas, chai 390ml', 18000, 'active', 'coca.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Product_detail`
--

CREATE TABLE `Product_detail` (
  `ID` int(10) NOT NULL,
  `ID_product` int(10) DEFAULT NULL,
  `ID_material` int(10) DEFAULT NULL,
  `Quantity` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Product_detail`
--

INSERT INTO `Product_detail` (`ID`, `ID_product`, `ID_material`, `Quantity`) VALUES
(1, 1, 1, 25),
(2, 1, 4, 10),
(3, 1, 12, 150),
(4, 2, 1, 20),
(5, 2, 2, 30),
(6, 2, 4, 5),
(7, 2, 12, 150),
(8, 3, 1, 10),
(9, 3, 2, 40),
(10, 3, 3, 50),
(11, 3, 12, 120),
(12, 4, 5, 10),
(13, 4, 7, 2),
(14, 4, 8, 1),
(15, 4, 4, 5),
(16, 4, 12, 150),
(17, 5, 6, 10),
(18, 5, 8, 1),
(19, 5, 4, 5),
(20, 5, 12, 150),
(21, 6, 5, 10),
(22, 6, 3, 50),
(23, 6, 2, 20),
(24, 6, 4, 5),
(25, 6, 12, 150),
(26, 7, 10, 150),
(27, 7, 2, 30),
(28, 7, 3, 50),
(29, 7, 12, 100),
(30, 8, 11, 150),
(31, 8, 2, 20),
(32, 8, 3, 50),
(33, 8, 12, 100),
(34, 9, 8, 1),
(35, 9, 4, 5),
(36, 9, 12, 100),
(37, 10, 9, 150),
(38, 10, 4, 5),
(39, 10, 12, 100),
(40, 11, 1, 20),
(41, 11, 3, 80),
(42, 11, 2, 30),
(43, 11, 12, 200),
(44, 12, 6, 15),
(45, 12, 3, 100),
(46, 12, 2, 20),
(47, 12, 12, 200),
(48, 13, 3, 100),
(49, 13, 2, 30),
(50, 13, 12, 200),
(51, 14, 13, 1),
(52, 15, 14, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Reviews`
--

CREATE TABLE `Reviews` (
  `ID` int(10) NOT NULL,
  `ID_order` int(10) DEFAULT NULL,
  `Rating` int(10) DEFAULT NULL,
  `Comment` text DEFAULT NULL,
  `Reply` text DEFAULT NULL,
  `Create_at` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Staff`
--

CREATE TABLE `Staff` (
  `ID` int(10) NOT NULL,
  `ID_account` int(10) DEFAULT NULL,
  `ID_brand` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Table_Coffee`
--

CREATE TABLE `Table_Coffee` (
  `ID` int(10) NOT NULL,
  `ID_Brach` int(10) DEFAULT NULL,
  `No` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `Account`
--
ALTER TABLE `Account`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `Branches`
--
ALTER TABLE `Branches`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `Cart`
--
ALTER TABLE `Cart`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `cart_ibfk_1` (`ID_Customer`);

--
-- Chỉ mục cho bảng `Cart_detail`
--
ALTER TABLE `Cart_detail`
  ADD KEY `ID_Cart` (`ID_Cart`),
  ADD KEY `ID_Product` (`ID_Product`);

--
-- Chỉ mục cho bảng `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `Coupons`
--
ALTER TABLE `Coupons`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `Coupon_usage`
--
ALTER TABLE `Coupon_usage`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_coupon` (`ID_coupon`),
  ADD KEY `ID_customer` (`ID_customer`);

--
-- Chỉ mục cho bảng `Customer_Profile`
--
ALTER TABLE `Customer_Profile`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_account` (`ID_account`);

--
-- Chỉ mục cho bảng `Inventory`
--
ALTER TABLE `Inventory`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_Material` (`ID_Material`),
  ADD KEY `ID_Brach` (`ID_Brach`);

--
-- Chỉ mục cho bảng `Material`
--
ALTER TABLE `Material`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_customer` (`ID_customer`),
  ADD KEY `ID_branch` (`ID_branch`),
  ADD KEY `ID_table` (`ID_table`);

--
-- Chỉ mục cho bảng `Order_detail`
--
ALTER TABLE `Order_detail`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_order` (`ID_order`);

--
-- Chỉ mục cho bảng `Payment`
--
ALTER TABLE `Payment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_order` (`ID_order`);

--
-- Chỉ mục cho bảng `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_category` (`ID_category`);

--
-- Chỉ mục cho bảng `Product_detail`
--
ALTER TABLE `Product_detail`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_product` (`ID_product`);

--
-- Chỉ mục cho bảng `Reviews`
--
ALTER TABLE `Reviews`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_order` (`ID_order`);

--
-- Chỉ mục cho bảng `Staff`
--
ALTER TABLE `Staff`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_account` (`ID_account`),
  ADD KEY `ID_brand` (`ID_brand`);

--
-- Chỉ mục cho bảng `Table_Coffee`
--
ALTER TABLE `Table_Coffee`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_Brach` (`ID_Brach`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `Account`
--
ALTER TABLE `Account`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `Branches`
--
ALTER TABLE `Branches`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `Cart`
--
ALTER TABLE `Cart`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `Categories`
--
ALTER TABLE `Categories`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `Coupons`
--
ALTER TABLE `Coupons`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Coupon_usage`
--
ALTER TABLE `Coupon_usage`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Customer_Profile`
--
ALTER TABLE `Customer_Profile`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `Inventory`
--
ALTER TABLE `Inventory`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Material`
--
ALTER TABLE `Material`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `Orders`
--
ALTER TABLE `Orders`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `Order_detail`
--
ALTER TABLE `Order_detail`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT cho bảng `Payment`
--
ALTER TABLE `Payment`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `Product`
--
ALTER TABLE `Product`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `Product_detail`
--
ALTER TABLE `Product_detail`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT cho bảng `Reviews`
--
ALTER TABLE `Reviews`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Staff`
--
ALTER TABLE `Staff`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Table_Coffee`
--
ALTER TABLE `Table_Coffee`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `Cart`
--
ALTER TABLE `Cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`ID_Customer`) REFERENCES `Account` (`ID`);

--
-- Các ràng buộc cho bảng `Cart_detail`
--
ALTER TABLE `Cart_detail`
  ADD CONSTRAINT `cart_detail_ibfk_1` FOREIGN KEY (`ID_Cart`) REFERENCES `Cart` (`ID`),
  ADD CONSTRAINT `cart_detail_ibfk_2` FOREIGN KEY (`ID_Product`) REFERENCES `Product` (`ID`);

--
-- Các ràng buộc cho bảng `Coupon_usage`
--
ALTER TABLE `Coupon_usage`
  ADD CONSTRAINT `coupon_usage_ibfk_1` FOREIGN KEY (`ID_coupon`) REFERENCES `Coupons` (`ID`),
  ADD CONSTRAINT `coupon_usage_ibfk_2` FOREIGN KEY (`ID_customer`) REFERENCES `Customer_Profile` (`ID`);

--
-- Các ràng buộc cho bảng `Customer_Profile`
--
ALTER TABLE `Customer_Profile`
  ADD CONSTRAINT `customer_profile_ibfk_1` FOREIGN KEY (`ID_account`) REFERENCES `Account` (`ID`);

--
-- Các ràng buộc cho bảng `Inventory`
--
ALTER TABLE `Inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`ID_Material`) REFERENCES `Material` (`ID`),
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`ID_Brach`) REFERENCES `Branches` (`ID`);

--
-- Các ràng buộc cho bảng `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`ID_customer`) REFERENCES `Customer_Profile` (`ID`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`ID_branch`) REFERENCES `Branches` (`ID`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`ID_table`) REFERENCES `Table_Coffee` (`ID`);

--
-- Các ràng buộc cho bảng `Order_detail`
--
ALTER TABLE `Order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`ID_order`) REFERENCES `orders` (`ID`);

--
-- Các ràng buộc cho bảng `Payment`
--
ALTER TABLE `Payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`ID_order`) REFERENCES `orders` (`ID`);

--
-- Các ràng buộc cho bảng `Product`
--
ALTER TABLE `Product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`ID_category`) REFERENCES `Categories` (`ID`);

--
-- Các ràng buộc cho bảng `Product_detail`
--
ALTER TABLE `Product_detail`
  ADD CONSTRAINT `product_detail_ibfk_1` FOREIGN KEY (`ID_product`) REFERENCES `Product` (`ID`);

--
-- Các ràng buộc cho bảng `Reviews`
--
ALTER TABLE `Reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`ID_order`) REFERENCES `orders` (`ID`);

--
-- Các ràng buộc cho bảng `Staff`
--
ALTER TABLE `Staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`ID_account`) REFERENCES `Account` (`ID`),
  ADD CONSTRAINT `staff_ibfk_2` FOREIGN KEY (`ID_brand`) REFERENCES `Branches` (`ID`);

--
-- Các ràng buộc cho bảng `Table_Coffee`
--
ALTER TABLE `Table_Coffee`
  ADD CONSTRAINT `table_coffee_ibfk_1` FOREIGN KEY (`ID_Brach`) REFERENCES `Branches` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
