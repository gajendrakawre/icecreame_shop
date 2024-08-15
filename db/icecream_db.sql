-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2024 at 07:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `icecream_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `qty` varchar(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `subject`, `message`) VALUES
('message01', 'pvnJSIfkYyN2yAnbQx9Y', 'John Doe', 'john@example.com', 'Order Inquiry', 'What is the status of my order?'),
('message02', 'pvnJSIfkYyN2yAnbQx9Y', 'Jane Smith', 'jane@example.com', 'Product Question', 'Do you have vegan options?'),
('message03', 'pvnJSIfkYyN2yAnbQx9Y', 'Alice Johnson', 'alice@example.com', 'Delivery Issue', 'My order was delayed.'),
('message04', 'pvnJSIfkYyN2yAnbQx9Y', 'Bob Brown', 'bob@example.com', 'Feedback', 'Great selection of flavors!'),
('message05', 'pvnJSIfkYyN2yAnbQx9Y', 'Carol White', 'carol@example.com', 'Order Problem', 'I received the wrong item.'),
('message06', 'pvnJSIfkYyN2yAnbQx9Y', 'David Green', 'david@example.com', 'Question', 'Can I customize my order?'),
('message07', 'pvnJSIfkYyN2yAnbQx9Y', 'Eva Black', 'eva@example.com', 'Delivery Time', 'When will my order arrive?'),
('message08', 'pvnJSIfkYyN2yAnbQx9Y', 'Frank Blue', 'frank@example.com', 'Product Availability', 'Is the product in stock?'),
('message09', 'pvnJSIfkYyN2yAnbQx9Y', 'Grace Yellow', 'grace@example.com', 'Order Confirmation', 'Did you receive my order?'),
('message10', 'pvnJSIfkYyN2yAnbQx9Y', 'Helen Red', 'helen@example.com', 'Customer Service', 'How can I contact support?');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `seller_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `address_type` varchar(10) NOT NULL,
  `method` varchar(50) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `qty` varchar(2) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'in progress',
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `seller_id`, `name`, `number`, `email`, `address`, `address_type`, `method`, `product_id`, `price`, `qty`, `date`, `status`, `payment_status`) VALUES
('order01', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'John Doe', '1234567890', 'john@example.com', '123 Elm St', 'home', 'Credit/Debit Card', 'product01', '5.00', '2', '2024-08-14', 'delivered', 'paid'),
('order02', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Jane Smith', '0987654321', 'jane@example.com', '456 Oak St', 'home', 'UPI/RuPay', 'product02', '6.00', '1', '2024-08-14', 'canceled', 'unpaid'),
('order03', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Alice Johnson', '5678901234', 'alice@example.com', '789 Pine St', 'office', 'Net Banking Transfer', 'product03', '5.50', '3', '2024-08-14', 'cancled', 'paid'),
('order10', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Helen Red', '3456789012', 'helen@example.com', '321 Maple St', 'home', 'GPay', 'product10', '5.50', '1', '2024-08-14', 'delivered', 'paid'),
('66bcf9ad01964', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Gajendra Kawre', '5656565656', 'user@example.com', 'R K B R ENCLAVE, BANJARA LAYOUT,3rd Floor,Bangalore,Karnataka,India,560043', 'home', 'gpay', 'product01', '5.00', '2', '2024-08-15', 'in progress', 'pending'),
('66bcf9ad0a4c4', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Gajendra Kawre', '5656565656', 'user@example.com', 'R K B R ENCLAVE, BANJARA LAYOUT,3rd Floor,Bangalore,Karnataka,India,560043', 'home', 'gpay', 'product02', '6.00', '1', '2024-08-15', 'in progress', 'pending'),
('66bcf9ad5e607', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Gajendra Kawre', '5656565656', 'user@example.com', 'R K B R ENCLAVE, BANJARA LAYOUT,3rd Floor,Bangalore,Karnataka,India,560043', 'home', 'gpay', 'product03', '5.50', '3', '2024-08-15', 'in progress', 'pending'),
('66bcf9ad732f9', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Gajendra Kawre', '5656565656', 'user@example.com', 'R K B R ENCLAVE, BANJARA LAYOUT,3rd Floor,Bangalore,Karnataka,India,560043', 'home', 'gpay', 'product04', '6.50', '2', '2024-08-15', 'in progress', 'pending'),
('66bcf9ad82305', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Gajendra Kawre', '5656565656', 'user@example.com', 'R K B R ENCLAVE, BANJARA LAYOUT,3rd Floor,Bangalore,Karnataka,India,560043', 'home', 'gpay', 'product05', '6.00', '1', '2024-08-15', 'in progress', 'pending'),
('66bcf9adb84b2', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Gajendra Kawre', '5656565656', 'user@example.com', 'R K B R ENCLAVE, BANJARA LAYOUT,3rd Floor,Bangalore,Karnataka,India,560043', 'home', 'gpay', 'product06', '6.50', '2', '2024-08-15', 'in progress', 'pending'),
('66bcf9add95ae', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Gajendra Kawre', '5656565656', 'user@example.com', 'R K B R ENCLAVE, BANJARA LAYOUT,3rd Floor,Bangalore,Karnataka,India,560043', 'home', 'gpay', 'product07', '6.00', '1', '2024-08-15', 'in progress', 'pending'),
('66bcf9ae00f35', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Gajendra Kawre', '5656565656', 'user@example.com', 'R K B R ENCLAVE, BANJARA LAYOUT,3rd Floor,Bangalore,Karnataka,India,560043', 'home', 'gpay', 'product08', '6.50', '2', '2024-08-15', 'in progress', 'pending'),
('66bcf9ae1d784', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Gajendra Kawre', '5656565656', 'user@example.com', 'R K B R ENCLAVE, BANJARA LAYOUT,3rd Floor,Bangalore,Karnataka,India,560043', 'home', 'gpay', 'product09', '6.00', '3', '2024-08-15', 'in progress', 'pending'),
('66bcf9ae67c46', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Gajendra Kawre', '5656565656', 'user@example.com', 'R K B R ENCLAVE, BANJARA LAYOUT,3rd Floor,Bangalore,Karnataka,India,560043', 'home', 'gpay', 'product10', '5.50', '1', '2024-08-15', 'in progress', 'pending'),
('66bcf9ae7f5e9', 'pvnJSIfkYyN2yAnbQx9Y', 'hEAnj9PV1OHuoXQlm7nX', 'Gajendra Kawre', '5656565656', 'user@example.com', 'R K B R ENCLAVE, BANJARA LAYOUT,3rd Floor,Bangalore,Karnataka,India,560043', 'home', 'gpay', 'product23', '6.00', '1', '2024-08-15', 'in progress', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` varchar(255) NOT NULL,
  `seller_id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `stock` int(100) NOT NULL,
  `product_detail` varchar(500) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `seller_id`, `name`, `price`, `image`, `stock`, `product_detail`, `status`) VALUES
('product01', 'hEAnj9PV1OHuoXQlm7nX', 'Vanilla Ice Cream', '5.00', 'type.jpg', 100, 'Classic vanilla ice cream.', 'active'),
('product02', 'hEAnj9PV1OHuoXQlm7nX', 'Chocolate Ice Cream', '6.00', 'categories2.jpg', 100, 'Rich chocolate ice cream.', 'active'),
('product03', 'hEAnj9PV1OHuoXQlm7nX', 'Strawberry Ice Cream', '5.50', 'type0.jpg', 100, 'Sweet strawberry ice cream.', 'active'),
('product04', 'hEAnj9PV1OHuoXQlm7nX', 'Mint Chocolate Chip', '6.50', 'type2.png', 100, 'Mint ice cream with chocolate chips.', 'active'),
('product05', 'hEAnj9PV1OHuoXQlm7nX', 'Cookie Dough', '6.00', 'type5.png', 100, 'Ice cream with cookie dough chunks.', 'active'),
('product06', 'hEAnj9PV1OHuoXQlm7nX', 'Butter Pecan', '6.50', 'mission.webp', 100, 'Rich butter pecan ice cream.', 'active'),
('product07', 'hEAnj9PV1OHuoXQlm7nX', 'Pistachio', '6.00', 'mission0.jpg', 100, 'Creamy pistachio ice cream.', 'active'),
('product08', 'hEAnj9PV1OHuoXQlm7nX', 'Caramel Swirl', '6.50', 'mission2.webp', 100, 'Delicious caramel swirl ice cream.', 'active'),
('product09', 'hEAnj9PV1OHuoXQlm7nX', 'Chocolate Mint', '6.00', '535405916_012c012ccc@2x.jpg', 100, 'Minty chocolate ice cream.', 'active'),
('product10', 'hEAnj9PV1OHuoXQlm7nX', 'Peach Sorbet', '5.50', '518151488_012c012ccc@2x.jpg', 100, 'Refreshing peach sorbet.', 'active'),
('product11', 'hEAnj9PV1OHuoXQlm7nX', 'Mango Tango', '6.00', 'boc.webp', 100, 'Tropical mango ice cream.', 'active'),
('product12', 'hEAnj9PV1OHuoXQlm7nX', 'Raspberry Ripple', '6.50', 'mission1.webp', 100, 'Tangy raspberry ice cream.', 'active'),
('product13', 'hEAnj9PV1OHuoXQlm7nX', 'Coconut Delight', '6.00', 'product12.jpg', 100, 'Smooth coconut ice cream.', 'active'),
('product14', 'hEAnj9PV1OHuoXQlm7nX', 'Lemon Sorbet', '5.50', 'taste.webp', 100, 'Zesty lemon sorbet.', 'active'),
('product15', 'hEAnj9PV1OHuoXQlm7nX', 'Almond Joy', '6.00', 'product8.jpg', 100, 'Ice cream with almonds and chocolate.', 'active'),
('product16', 'hEAnj9PV1OHuoXQlm7nX', 'Blackberry Cheesecake', '6.50', 'product5.jpg', 100, 'Cheesecake with blackberry flavor.', 'active'),
('product17', 'hEAnj9PV1OHuoXQlm7nX', 'Tiramisu', '6.00', 'product9.jpg', 100, 'Classic tiramisu ice cream.', 'active'),
('product18', 'hEAnj9PV1OHuoXQlm7nX', 'Espresso', '6.50', 'product7.jpg', 100, 'Bold espresso ice cream.', 'active'),
('product19', 'hEAnj9PV1OHuoXQlm7nX', 'Pecan Pie', '6.00', '514215896_012c012ccc@2x.jpg', 100, 'Ice cream with pecan pie flavor.', 'active'),
('product20', 'hEAnj9PV1OHuoXQlm7nX', 'Maple Walnut', '6.50', 'product10.jpg', 100, 'Maple ice cream with walnuts.', 'active'),
('product21', 'hEAnj9PV1OHuoXQlm7nX', 'Toffee Crunch', '6.00', 'product0.jpg', 100, 'Ice cream with toffee bits.', 'active'),
('product22', 'hEAnj9PV1OHuoXQlm7nX', 'Peanut Butter Cup', '6.50', 'product14-removebg-preview.png', 100, 'Peanut butter ice cream with chocolate cups.', 'active'),
('product23', 'hEAnj9PV1OHuoXQlm7nX', 'Pumpkin Spice', '6.00', 'product15-removebg-preview.png', 100, 'Seasonal pumpkin spice ice cream.', 'active'),
('product24', 'hEAnj9PV1OHuoXQlm7nX', 'Cherry Garcia', '6.50', '687180662_012c012ccc@2x.jpg', 100, 'Cherry ice cream with chocolate chunks.', 'active'),
('product25', 'hEAnj9PV1OHuoXQlm7nX', 'S’mores', '6.00', '547235148_012c012ccc@2x.jpg', 100, 'Ice cream with graham cracker and marshmallow.', 'active'),
('product26', 'hEAnj9PV1OHuoXQlm7nX', 'Raspberry Sorbet', '4.50', 'product3-removebg-preview.png', 100, 'Raspberry-flavored sorbet. Refreshing raspberry sorbet made with real raspberries. A tangy and fruity treat for hot days.', 'active'),
('product27', 'hEAnj9PV1OHuoXQlm7nX', 'Peach Ice Cream', '4.75', 'product.webp', 80, 'Peach-flavored ice cream. Smooth peach ice cream made with ripe peaches and cream. Sweet and summery, perfect for dessert.', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE `sellers` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`id`, `name`, `email`, `password`, `image`) VALUES
('hEAnj9PV1OHuoXQlm7nX', 'Gajendra Kawre (Spartans)', 'admin@example.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'esKU8m8LWSs0dijeHruV.jpeg'),
('hEAnj9PV1OHuoXQlm7nX', 'Ice Cream Delights', 'info@icecreamd.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'seller1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`) VALUES
('pvnJSIfkYyN2yAnbQx9Y', 'Gajendra Kawre (Spartans)', 'user@example.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'IqvPBGIICmgQlIoDEMgB.jpeg'),
('pvnJSIfkYyN2yAnbQx9Y', 'John Doe', 'john@example.com', 'password123', 'user1.jpg'),
('user02', 'Jane Smith', 'jane@example.com', 'password456', 'user2.jpg'),
('user10', 'Helen Red', 'helen@example.com', 'password789', 'user10.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `price`) VALUES
('sFxWqpviaIMQMOQZeQxE', 'pvnJSIfkYyN2yAnbQx9Y', 'product01', '5.00'),
('vGeUcQRDXJFBXlbRFA6D', 'pvnJSIfkYyN2yAnbQx9Y', 'product02', '6.00'),
('YmWGpGkZEIIMfHVuwd8A', 'pvnJSIfkYyN2yAnbQx9Y', 'product03', '5.50');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
