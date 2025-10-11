-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th10 11, 2025 lúc 05:35 PM
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
  `Fullname` varchar(100) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `Phone` int(10) DEFAULT NULL,
  `Role` int(10) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Branches`
--

CREATE TABLE `Branches` (
  `ID` int(10) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Address` varchar(1000) DEFAULT NULL,
  `Phone` int(10) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Categories`
--

CREATE TABLE `Categories` (
  `ID` int(10) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `Address` varchar(100) DEFAULT NULL,
  `Points` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Order_Coffee`
--

CREATE TABLE `Order_Coffee` (
  `ID` int(10) NOT NULL,
  `ID_customer` int(10) DEFAULT NULL,
  `ID_branch` int(10) DEFAULT NULL,
  `ID_table` int(10) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL,
  `Time` time DEFAULT NULL,
  `Payment_status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `Status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Chỉ mục cho bảng `Order_Coffee`
--
ALTER TABLE `Order_Coffee`
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
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Branches`
--
ALTER TABLE `Branches`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Categories`
--
ALTER TABLE `Categories`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

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
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Inventory`
--
ALTER TABLE `Inventory`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Material`
--
ALTER TABLE `Material`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Order_Coffee`
--
ALTER TABLE `Order_Coffee`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Order_detail`
--
ALTER TABLE `Order_detail`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Payment`
--
ALTER TABLE `Payment`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Product`
--
ALTER TABLE `Product`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `Product_detail`
--
ALTER TABLE `Product_detail`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

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
-- Các ràng buộc cho bảng `Order_Coffee`
--
ALTER TABLE `Order_Coffee`
  ADD CONSTRAINT `order_coffee_ibfk_1` FOREIGN KEY (`ID_customer`) REFERENCES `Customer_Profile` (`ID`),
  ADD CONSTRAINT `order_coffee_ibfk_2` FOREIGN KEY (`ID_branch`) REFERENCES `Branches` (`ID`),
  ADD CONSTRAINT `order_coffee_ibfk_3` FOREIGN KEY (`ID_table`) REFERENCES `Table_Coffee` (`ID`);

--
-- Các ràng buộc cho bảng `Order_detail`
--
ALTER TABLE `Order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`ID_order`) REFERENCES `Order_Coffee` (`ID`);

--
-- Các ràng buộc cho bảng `Payment`
--
ALTER TABLE `Payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`ID_order`) REFERENCES `Order_Coffee` (`ID`);

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
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`ID_order`) REFERENCES `Order_Coffee` (`ID`);

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
