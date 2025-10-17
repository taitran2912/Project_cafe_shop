-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th10 17, 2025 lúc 06:54 PM
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
(2, 1, 2),
(2, 2, 1),
(2, 3, 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `Cart_detail`
--
ALTER TABLE `Cart_detail`
  ADD KEY `ID_Cart` (`ID_Cart`),
  ADD KEY `ID_Product` (`ID_Product`);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `Cart_detail`
--
ALTER TABLE `Cart_detail`
  ADD CONSTRAINT `cart_detail_ibfk_1` FOREIGN KEY (`ID_Cart`) REFERENCES `Cart` (`ID`),
  ADD CONSTRAINT `cart_detail_ibfk_2` FOREIGN KEY (`ID_Product`) REFERENCES `Product` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
