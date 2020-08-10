-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 24, 2019 at 06:38 PM
-- Server version: 5.6.41-84.1-log
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skyfo9g2_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcement_details`
--

CREATE TABLE `announcement_details` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `message` longtext NOT NULL,
  `image` longtext,
  `url` longtext,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `announcement_details`
--

INSERT INTO `announcement_details` (`id`, `title`, `message`, `image`, `url`, `created`) VALUES
(5, 'Sky Winner - New Version Launch Today', 'Restrict access to the app for added security \r\nWeâ€™ve included geo-fencing in this new app. This enables you to restrict access to specific areas of the app when staff are not present at your business location. Staff will not be able to access appointment information, which means they cannot alter things like appointment notes, digital form data, rebookings, their check-in and check-out times. They will however still be able to access data around their payroll, commissions, tips, and other reports. Managers and owners will continue to have anytime, anywhere access to their app.', 'images/31422_005.jpg', 'https://codecanyon.net/item/pubg-tournament-app/23898180', '2019-12-11 11:52:08'),
(6, 'Sky Earning - New Lottery App Launch Tommorow', 'Mega Millions and Powerball are available for play in states where we are live! You are also able to view winning numbers, draw dates, and jackpot totals for hundreds of other lottery games.', 'images/41921_PosterEarnMoney-03.jpg', 'https://codecanyon.net/item/pubg-tournament-app/23898180', '2019-12-11 11:55:11');

-- --------------------------------------------------------

--
-- Table structure for table `match_details`
--

CREATE TABLE `match_details` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `time` text NOT NULL,
  `imgBanner` text,
  `winPrize` int(11) NOT NULL,
  `imgCover` text NOT NULL,
  `perKill` int(11) NOT NULL,
  `entryFee` int(11) NOT NULL,
  `matchType` text NOT NULL,
  `version` text NOT NULL,
  `map` text NOT NULL,
  `isPrivateMatch` text NOT NULL,
  `entryType` text NOT NULL,
  `sponsoredBy` text NOT NULL,
  `spectateURL` text NOT NULL,
  `matchNotes` text,
  `match_status` text NOT NULL COMMENT '0 for upcoming   1 for ongoing   2 for finished',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `matchRules` text NOT NULL,
  `match_desc` longtext,
  `is_cancel` enum('0','1') NOT NULL DEFAULT '0',
  `cancel_reason` longtext,
  `private_match_code` int(11) DEFAULT NULL,
  `access_token` longtext,
  `platform` varchar(50) DEFAULT NULL,
  `is_del` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `match_details`
--

INSERT INTO `match_details` (`id`, `title`, `time`, `imgBanner`, `winPrize`, `imgCover`, `perKill`, `entryFee`, `matchType`, `version`, `map`, `isPrivateMatch`, `entryType`, `sponsoredBy`, `spectateURL`, `matchNotes`, `match_status`, `created`, `matchRules`, `match_desc`, `is_cancel`, `cancel_reason`, `private_match_code`, `access_token`, `platform`, `is_del`) VALUES
(259, 'Solo Match #1', '22/12/2019 at 10:10 PM', NULL, 50, '7', 5, 0, 'Solo', 'TPP', 'Engale', 'no', 'Free', 'skywinner', 'www.youtube.com', NULL, '0', '2019-12-24 18:35:10', '4', NULL, '0', NULL, NULL, 'fde0ecd513c03e26f67c76603a5f9a79', 'Mobile', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notification_details`
--

CREATE TABLE `notification_details` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `message` longtext NOT NULL,
  `image` longtext,
  `url` longtext,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `participant_details`
--

CREATE TABLE `participant_details` (
  `id` int(11) NOT NULL,
  `match_id` text,
  `user_id` text,
  `pubg_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `access_key` text,
  `name` text,
  `kills` int(11) NOT NULL DEFAULT '0',
  `win` int(11) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `prize` int(11) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `slot` varchar(30) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `referral_details`
--

CREATE TABLE `referral_details` (
  `id` int(11) NOT NULL,
  `username` text,
  `refer_points` int(11) NOT NULL DEFAULT '0',
  `refer_code` text,
  `refer_status` tinytext,
  `refer_date` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rewarded_details`
--

CREATE TABLE `rewarded_details` (
  `id` int(11) NOT NULL,
  `username` text,
  `reward_points` int(11) DEFAULT '0',
  `reward_date` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `room_details`
--

CREATE TABLE `room_details` (
  `id` int(11) NOT NULL,
  `match_id` text,
  `room_id` text,
  `room_pass` text,
  `room_status` text COMMENT '0 for hide 1 for show',
  `room_size` int(11) DEFAULT '0',
  `total_joined` int(11) DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room_details`
--

INSERT INTO `room_details` (`id`, `match_id`, `room_id`, `room_pass`, `room_status`, `room_size`, `total_joined`, `created`) VALUES
(254, '259', 'sky123', 'sky123', NULL, 100, 0, '2019-12-24 18:35:10');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_about`
--

CREATE TABLE `tbl_about` (
  `id` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `add_by` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `modify_by` int(11) DEFAULT NULL,
  `date_modify` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_about`
--

INSERT INTO `tbl_about` (`id`, `content`, `add_by`, `date_created`, `modify_by`, `date_modify`) VALUES
(1, '<p>SkyWinner is an Ultimate Solution to all your eSports Games.</p>\r\n\r\n<p>SkyWinner is an Online Portal which Offers Rewards and Unlimited Entertainment for Participating and Playing Games Online. Users can play online multiple games like PUBG Mobile, Free Fire, etc. and Earn Cash Rewards and Prizes based on their in-game performance..</p>\r\n\r\n<p>Founded and Launched on 9th Jun&nbsp;2019, was developed by two young minds from Gujarat, India!</p>\r\n\r\n<p>You might be addicted to Online Games but just think what if you can start making money or living by Playing Mobile Games? Well, this is what SkyWinner Offers. Users can participate in the upcoming eSports games and Win Amazing Prizes and Rewards.</p>\r\n\r\n<p>Participate in the Tournaments of Games like PUBG Mobile, Free Fire, Fortnite, etc. and Earn Huge Rewards Daily. Users can join Custom Rooms, and Get Rewards for Chicken Dinner and also for each Kill they Score. Sounds cool, huh?? Give it a try!</p>\r\n', 9, '2019-01-08 19:01:25', 6, '2019-08-02 09:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_app_details`
--

CREATE TABLE `tbl_app_details` (
  `id` int(11) NOT NULL,
  `app_name` varchar(100) DEFAULT 'NULL',
  `logo` longtext,
  `favicon` longtext,
  `app_url` longtext
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_app_details`
--

INSERT INTO `tbl_app_details` (`id`, `app_name`, `logo`, `favicon`, `app_url`) VALUES
(1, 'skywinner', 'upload/5d349473274db9.36216141.logo4.png', 'upload/5d349473282501.03996887.favicon.png', 'http://yourdomain.com/app.apk');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contact`
--

CREATE TABLE `tbl_contact` (
  `contact_id` int(11) NOT NULL,
  `title` longtext NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(70) NOT NULL,
  `address` longtext NOT NULL,
  `other` longtext NOT NULL,
  `date_created` datetime NOT NULL,
  `added_by` int(11) NOT NULL,
  `date_modify` datetime DEFAULT NULL,
  `modify_by` int(11) DEFAULT NULL,
  `whatsapp_no` varchar(20) NOT NULL,
  `messenger_id` varchar(200) NOT NULL,
  `fb_follow` varchar(200) NOT NULL,
  `ig_follow` varchar(200) NOT NULL,
  `twitter_follow` varchar(200) NOT NULL,
  `youtube_follow` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_contact`
--

INSERT INTO `tbl_contact` (`contact_id`, `title`, `phone`, `email`, `address`, `other`, `date_created`, `added_by`, `date_modify`, `modify_by`, `whatsapp_no`, `messenger_id`, `fb_follow`, `ig_follow`, `twitter_follow`, `youtube_follow`) VALUES
(1, 'SkyWinner is an Ultimate Solution to all your eSports Games.', '7990353366', 'skyforcoding@gmail.com', 'A/1 xyz complex,\r\nAlkapuri, Sarabhai Main Road,\r\nVadodara 397001\r\nGujarat - India', 'https://tawk.to/chat/5d25bb797a48df6da243d8ce/default\r\n', '2019-01-08 20:01:09', 9, '2019-07-18 01:07:57', 6, '+919974258844', '100024589101839', 'www.facebook.com/bearanker', 'www.facebook.com/bearanker', 'www.twitter.com', 'www.youtube.com');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_image`
--

CREATE TABLE `tbl_image` (
  `img_id` int(11) NOT NULL,
  `image_name` varchar(250) DEFAULT NULL,
  `image` longtext,
  `date_created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_image`
--

INSERT INTO `tbl_image` (`img_id`, `image_name`, `image`, `date_created`) VALUES
(8, 'Duo Cover Image', 'upload/5e025a041e3b35.38274819.pubg-battleground-image-4k.jpg', '2019-12-24 18:33:40'),
(7, 'Solo Cover Image', 'upload/5e025a1584ad66.07298257.hq-pubg-image-for-printing.jpg', '2019-12-24 18:33:57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payouts`
--

CREATE TABLE `tbl_payouts` (
  `id` int(10) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `coins` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0' COMMENT '0=active,1=inactive',
  `mode` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=debit, 1=credit',
  `currency` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_payouts`
--

INSERT INTO `tbl_payouts` (`id`, `name`, `subtitle`, `message`, `amount`, `coins`, `image`, `status`, `mode`, `currency`) VALUES
(19, 'PayPal', '350 Coins = 5 USD', 'Enter your paypal id', '5', '350', 'upload/5df0d121b1c702.03544957.paypal-logo-20071.png', '0', '0', 'USD'),
(30, 'GooglePay', '10 Coins = 10 INR', 'Enter your upi id', '10', '10', 'upload/5df0d489457c43.77766408.google-pay-logo-5aeb6a99a18d9e0037f978bf.jpg', '0', '1', 'INR'),
(29, 'GooglePay', '10 Coins = 10 INR', 'Enter your upi id', '10', '10', 'upload/5df0d473a601a1.22185388.google-pay-logo-5aeb6a99a18d9e0037f978bf.jpg', '0', '0', 'INR'),
(12, 'PayPal', '700 Coins = 10 USD', 'Enter your paypal id', '10', '700', 'upload/5df0d108c09ae0.56186646.paypal-logo-20071.png', '0', '1', 'USD'),
(25, 'PayTm', '50 Coins = 50 INR', 'Enter your paytm number', '50', '50', 'upload/5df0d17ae76c59.35822246.paytm-logo.jpg', '0', '1', 'INR'),
(26, 'PayTm', '10 Coins = 10 INR', 'Enter your paytm number', '10', '10', 'upload/5df0d1576e8007.61654825.paytm-logo.jpg', '0', '0', 'INR'),
(31, 'UPI', '50 Coins = 50 INR', 'Enter your upi id', '50', '50', 'upload/5df0d4b84e2093.71828059.upi-logo.jpg', '0', '0', 'INR'),
(32, 'UPI', '50 Coins = 50 INR', 'Enter your upi id', '50', '50', 'upload/5df0d4f43e5e69.47163227.upi-logo.jpg', '0', '1', 'INR');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_privacy_policy`
--

CREATE TABLE `tbl_privacy_policy` (
  `id` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `add_by` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `modify_by` int(11) NOT NULL,
  `date_modify` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_privacy_policy`
--

INSERT INTO `tbl_privacy_policy` (`id`, `content`, `add_by`, `date_created`, `modify_by`, `date_modify`) VALUES
(1, '<p>SkyWinner built the SkyWinner app as a Free app. This SERVICE is provided by SkyWinner at no cost and is intended for use as is.</p>\r\n\r\n<p>This page is used to inform visitors regarding my policies with the collection, use, and disclosure of Personal Information if anyone decided to use my Service.</p>\r\n\r\n<p>If you choose to use my Service, then you agree to the collection and use of information in relation to this policy. The Personal Information that I collect is used for providing and improving the Service. I will not use or share your information with anyone except as described in this Privacy Policy.</p>\r\n\r\n<p>The terms used in this Privacy Policy have the same meanings as in our Terms and Conditions, which is accessible at SkyWinner unless otherwise defined in this Privacy Policy.</p>\r\n\r\n<p><strong>Information Collection and Use</strong></p>\r\n\r\n<p>For a better experience, while using our Service, I may require you to provide us with certain personally identifiable information, including but not limited to Name,Phone,Email,Password. The information that I request will be retained on your device and is not collected by me in any way.</p>\r\n\r\n<p>The app does use third party services that may collect information used to identify you.</p>\r\n\r\n<p>Link to privacy policy of third party service providers used by the app</p>\r\n\r\n<ul>\r\n	<li><a href=\"https://www.google.com/policies/privacy/\" target=\"_blank\">Google Play Services</a></li>\r\n</ul>\r\n\r\n<p><strong>Log Data</strong></p>\r\n\r\n<p>I want to inform you that whenever you use my Service, in a case of an error in the app I collect data and information (through third party products) on your phone called Log Data. This Log Data may include information such as your device Internet Protocol (&ldquo;IP&rdquo;) address, device name, operating system version, the configuration of the app when utilizing my Service, the time and date of your use of the Service, and other statistics.</p>\r\n\r\n<p><strong>Cookies</strong></p>\r\n\r\n<p>Cookies are files with a small amount of data that are commonly used as anonymous unique identifiers. These are sent to your browser from the websites that you visit and are stored on your device&rsquo;s internal memory.</p>\r\n\r\n<p>This Service does not use these &ldquo;cookies&rdquo; explicitly. However, the app may use third party code and libraries that use &ldquo;cookies&rdquo; to collect information and improve their services. You have the option to either accept or refuse these cookies and know when a cookie is being sent to your device. If you choose to refuse our cookies, you may not be able to use some portions of this Service.</p>\r\n\r\n<p><strong>Service Providers</strong></p>\r\n\r\n<p>I may employ third-party companies and individuals due to the following reasons:</p>\r\n\r\n<ul>\r\n	<li>To facilitate our Service;</li>\r\n	<li>To provide the Service on our behalf;</li>\r\n	<li>To perform Service-related services; or</li>\r\n	<li>To assist us in analyzing how our Service is used.</li>\r\n</ul>\r\n\r\n<p>I want to inform users of this Service that these third parties have access to your Personal Information. The reason is to perform the tasks assigned to them on our behalf. However, they are obligated not to disclose or use the information for any other purpose.</p>\r\n\r\n<p><strong>Security</strong></p>\r\n\r\n<p>I value your trust in providing us your Personal Information, thus we are striving to use commercially acceptable means of protecting it. But remember that no method of transmission over the internet, or method of electronic storage is 100% secure and reliable, and I cannot guarantee its absolute security.</p>\r\n\r\n<p><strong>Links to Other Sites</strong></p>\r\n\r\n<p>This Service may contain links to other sites. If you click on a third-party link, you will be directed to that site. Note that these external sites are not operated by me. Therefore, I strongly advise you to review the Privacy Policy of these websites. I have no control over and assume no responsibility for the content, privacy policies, or practices of any third-party sites or services.</p>\r\n\r\n<p><strong>Children&rsquo;s Privacy</strong></p>\r\n\r\n<p>These Services do not address anyone under the age of 13. I do not knowingly collect personally identifiable information from children under 13. In the case I discover that a child under 13 has provided me with personal information, I immediately delete this from our servers. If you are a parent or guardian and you are aware that your child has provided us with personal information, please contact me so that I will be able to do necessary actions.</p>\r\n\r\n<p><strong>Changes to This Privacy Policy</strong></p>\r\n\r\n<p>I may update our Privacy Policy from time to time. Thus, you are advised to review this page periodically for any changes. I will notify you of any changes by posting the new Privacy Policy on this page. These changes are effective immediately after they are posted on this page.</p>\r\n\r\n<p><strong>Contact Us</strong></p>\r\n\r\n<p>If you have any questions or suggestions about my Privacy Policy, do not hesitate to contact me.</p>\r\n', 0, '2019-07-16 00:00:00', 6, '2019-08-02 10:00:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_push_notification`
--

CREATE TABLE `tbl_push_notification` (
  `id` int(11) NOT NULL,
  `appid` longtext NOT NULL,
  `auth_key` longtext NOT NULL,
  `add_by` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `modify_by` int(11) NOT NULL,
  `date_modify` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_push_notification`
--

INSERT INTO `tbl_push_notification` (`id`, `appid`, `auth_key`, `add_by`, `date_created`, `modify_by`, `date_modify`) VALUES
(1, 'b51eec07-7234-480f-ad9b-600136130cc2', 'YjBmNjc0ZDAtNDRmYi00M2VjLTgzMWEtMmQwNzYzZTk1YjM5', 0, '2019-07-19 00:00:00', 1, '2019-07-19 05:36:43');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rules`
--

CREATE TABLE `tbl_rules` (
  `rule_id` int(11) NOT NULL,
  `rule_title` varchar(250) NOT NULL,
  `rules` longtext,
  `date_created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_rules`
--

INSERT INTO `tbl_rules` (`rule_id`, `rule_title`, `rules`, `date_created`) VALUES
(4, 'Solo Match Rulse', '<ul>\r\n	<li>You accepted our <a href=\"https://skyforcoding.in/terms-and-conditions.html\">TERMS AND CONDITIONS</a> and <a href=\"https://skyforcoding.in/fair-play.html\">FAIR PLAY POLICY</a>by joining this match</li>\r\n	<li>Teaming with other players or unregistered players entering room will result in permanent ban from further contests</li>\r\n	<li>We\'ll be spectating each player so if you got caught breaking any rules you&#39;ll be disqualified.</li>\r\n	<li>skyforcoding has right to remove any participant at its sole discretion to ensure fairplay</li>\r\n	<li>skyforcoding has right to remove any participant whose linked skyforcoding username is incorrect</li>\r\n	<li>You are requested to join the room before match start time</li>\r\n	<li>If you disconnected from ongoning match due to low bettory or internet connection then it&#39;s your responsblity</li>\r\n	<li>In solo match if are playing in team with your friend, then both are discollified</li>\r\n	<li>Joining fees is completely non-refundable</li>\r\n	<li>Prize money can only be transfered to linked PayTM account</li>\r\n	<li>And please report in our WhatsApp group if you caught any hacker or cheating we&#39;ll take immediate action!</li>\r\n	<li>Screenshot should ne require to claim a reward</li>\r\n</ul>\r\n', '2019-12-19 18:29:11'),
(5, 'Duo Match Rules', '<ul>\r\n	<li>You accepted our <a href=\"https://skyforcoding.in/terms-and-conditions.html\">TERMS AND CONDITIONS</a> and <a href=\"https://skyforcoding.in/fair-play.html\">FAIR PLAY POLICY</a>by joining this match</li>\r\n	<li>Teaming with other players or unregistered players entering room will result in permanent ban from further contests</li>\r\n	<li>We\'ll be spectating each player so if you got caught breaking any rules you&#39;ll be disqualified.</li>\r\n	<li>skyforcoding has right to remove any participant at its sole discretion to ensure fairplay</li>\r\n	<li>skyforcoding has right to remove any participant whose linked skyforcoding username is incorrect</li>\r\n	<li>You are requested to join the room before match start time</li>\r\n	<li>If you disconnected from ongoning match due to low bettory or internet connection then it&#39;s your responsblity</li>\r\n	<li>In solo match if are playing in team with your friend, then both are discollified</li>\r\n	<li>Joining fees is completely non-refundable</li>\r\n	<li>Prize money can only be transfered to linked PayTM account</li>\r\n	<li>And please report in our WhatsApp group if you caught any hacker or cheating we&#39;ll take immediate action!</li>\r\n	<li>Screenshot should ne require to claim a reward</li>\r\n</ul>\r\n', '2019-12-19 18:28:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_terms_conditions`
--

CREATE TABLE `tbl_terms_conditions` (
  `id` int(11) NOT NULL,
  `content` longtext,
  `add_by` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `modify_by` int(11) DEFAULT NULL,
  `date_modify` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_terms_conditions`
--

INSERT INTO `tbl_terms_conditions` (`id`, `content`, `add_by`, `date_created`, `modify_by`, `date_modify`) VALUES
(1, '<p><strong>Our Policies</strong></p>\r\n\r\n<p>Our focus is complete customer satisfaction. In the event, if you are displeased with the services provided, we will refund back the money, provided the reasons are genuine and proved after investigation. Please read the fine rules of each match before joining it, it provides all the details about the match.</p>\r\n\r\n<p>In case of dissatisfaction from our services, clients have the liberty to cancel their projects and request a refund from us. Our Policy for the cancellation and refund will be as follows:</p>\r\n\r\n<p><strong>Cancellation Policy</strong></p>\r\n\r\n<p>For Cancellations please contact the us via&nbsp;Whatsapp</p>\r\n\r\n<p>Requests received later than 7 business days prior to the end of the current service period will be treated as cancellation of services for the next service period.</p>\r\n\r\n<p><strong>Refund Policy</strong></p>\r\n\r\n<p>We will try our best to create the suitable design concepts for our clients. In case any client is not completely satisfied with our rules and match we can provide a refund.</p>\r\n\r\n<p>If paid by credit card &amp; debit card, refunds will be issued to the original credit card &amp; debit card provided at the time of joining match and in case of payment gateway name payments refund will be made to the same account.</p>\r\n\r\n<p><strong>Fair Policies</strong></p>\r\n\r\n<ul>\r\n	<li><strong>SkyWinner</strong>&nbsp;Reserves right to cancel any match at anytime without any prior notice or reason.</li>\r\n	<li>In case the invesigators or spectators find any suspicious player in the gameplay, DreamWinner reserves right to disquaify, suspend or put the doer&rsquo;s account on hold and also cancel their winning prizes or amount.</li>\r\n	<li>In case of any technical glitch, the match may get cancelled and in such cases the entry fees will be refunded to the participants or re-match may take place.</li>\r\n	<li>In case any player fails to submit correct details while participating in any match then the reward/prize amount will be cancelled for that player and no refund will be processed.</li>\r\n	<li>Players are not allowed to use any Mod or Hack in the Gameplay. If anyone is found suspicious, the immediate action will be taken, the account will be suspended, all the pending withdrawals will be cancelled and wallet balance will become useless.</li>\r\n	<li>Do not use any Emulator to Play in non-emulator matches. Such players will be kicked and if remains un-kicked then they may not get the match rewards/prizes.</li>\r\n	<li>Participants need to make sure to grab Match Details such as Room ID and Password before the match start time. The Room Id and Password are shared in the app and also through SMS. Grab them and join the room quickly. In case the participants fail to join the room before the match start time, they won&rsquo;t be able to join the match later on and no refund will be processed for the same.</li>\r\n	<li>In case participants fail to join the room due to any external issues from participants end (incl. Slow internet, multi-tasking, minimizing PUBG Mobile, Receiving Calls or SMS, etc.) then no refund will be processed.</li>\r\n	<li>If the participants are kicked or auto-kicked from the room without any reasons then the refund will be issued upon submitting proper valid evidences such as Screenshot of the Error Screen or Message.</li>\r\n	<li>Any of the policies mentioned here can change, update or removed at any time, so keep checking here for latest policies and rules.</li>\r\n</ul>\r\n', 0, '2019-07-16 00:00:00', 6, '2019-08-01 03:41:14');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_master`
--

CREATE TABLE `tbl_user_master` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(60) NOT NULL,
  `uname` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `forgot_pass_identity` longtext,
  `email` varchar(100) NOT NULL,
  `user_designation` varchar(100) NOT NULL,
  `user_role` varchar(100) NOT NULL,
  `dob` date DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `alt_phone` varchar(30) DEFAULT NULL,
  `address` longtext,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `profile_pic` longtext,
  `verification_code` longtext NOT NULL,
  `account_status` enum('0','1') DEFAULT NULL COMMENT '1=active',
  `is_verify` enum('0','1') DEFAULT NULL COMMENT '1=verify',
  `del` enum('0','1') NOT NULL COMMENT '1=delete',
  `added_by` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `modify_by` int(11) DEFAULT NULL,
  `date_modify` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  `del_by` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user_master`
--

INSERT INTO `tbl_user_master` (`user_id`, `fname`, `lname`, `uname`, `password`, `forgot_pass_identity`, `email`, `user_designation`, `user_role`, `dob`, `phone`, `alt_phone`, `address`, `city`, `state`, `country`, `profile_pic`, `verification_code`, `account_status`, `is_verify`, `del`, `added_by`, `date_created`, `modify_by`, `date_modify`, `last_login`, `del_by`) VALUES
(1, 'Sky', 'Winner', 'skywinner', '71148077832098f0ea5bca0a0981250d8f4345cd', '9b57c92e538d4313e4f6508e7bae3e61', 'skyforcoding@gmail.com', 'developer12', 'limited', '1212-12-12', '886644552212', '12', 'vadodara12', 'Vaodara12', 'Gujarat12', 'India12', 'upload/5c76888a4a3384.59994936.expertise.jpg', '&qSuPjbCwRxn#$BO', '1', '1', '0', 0, '2019-02-27 12:54:34', 1, '2019-12-24 15:48:44', NULL, NULL),
(6, 'Sky', 'Coder', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '9b57c92e538d4313e4f6508e7bae3e61', 'skyforcoding@gmail.com', 'developer', 'admin', NULL, '8866445522', '', 'vadodara', 'Vaodara', 'Gujarat', 'India', 'upload/5c7779469f7b50.20408020.bg6.jpg', 'zJfpCX9lHIqsnFPI', '1', '1', '0', 0, '2019-02-28 06:01:42', NULL, '2019-12-24 15:48:23', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` int(11) NOT NULL,
  `user_id` text,
  `order_id` text,
  `payment_id` text,
  `amount` text,
  `remark` text,
  `type` text,
  `date` varchar(255) DEFAULT NULL,
  `wallet` text,
  `coins` int(11) DEFAULT '0',
  `account_holder_name` varchar(255) DEFAULT NULL,
  `account_holder_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `update_details`
--

CREATE TABLE `update_details` (
  `id` int(11) NOT NULL,
  `force_update` tinytext,
  `whats_new` longtext,
  `update_date` date DEFAULT NULL,
  `latest_version_name` text,
  `update_url` longtext
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `update_details`
--

INSERT INTO `update_details` (`id`, `force_update`, `whats_new`, `update_date`, `latest_version_name`, `update_url`) VALUES
(1, '1', '<ul>\r\n	<li><strong>Bug Fixed</strong></li>\r\n	<li><strong>New Layout</strong></li>\r\n	<li><strong>Improved User Interface</strong></li>\r\n	<li><strong>Reduced App Size</strong></li>\r\n	<li><strong>Added More Ways to Earn1</strong></li>\r\n</ul>\r\n', '2019-07-31', '4', 'http://yourdomain.com/app.apk');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `fname` text,
  `lname` text,
  `username` text,
  `password` text,
  `email` text,
  `mobile` text,
  `promocode` text,
  `user_type` text,
  `cur_balance` int(11) DEFAULT '0',
  `won_balance` int(11) DEFAULT '0',
  `status` text,
  `modified_date` text,
  `created_date` text,
  `code` text,
  `refer` text,
  `referer` text,
  `refered` int(11) DEFAULT '0',
  `whatsapp_num` varchar(20) DEFAULT NULL,
  `pubg_username` varchar(50) DEFAULT NULL,
  `forgot_pass_identity` longtext,
  `bonus_balance` int(11) NOT NULL DEFAULT '0',
  `user_profile` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `device_id` varchar(255) DEFAULT NULL,
  `is_block` varchar(255) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `fname`, `lname`, `username`, `password`, `email`, `mobile`, `promocode`, `user_type`, `cur_balance`, `won_balance`, `status`, `modified_date`, `created_date`, `code`, `refer`, `referer`, `refered`, `whatsapp_num`, `pubg_username`, `forgot_pass_identity`, `bonus_balance`, `user_profile`, `gender`, `dob`, `device_id`, `is_block`) VALUES
(22, 'Test', 'User', 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'skyforcoding@gmail.com', '9974258844', 'user', 'user', 984, 96, '1', '2019-07-01', '2019-07-01', '', 'user', '', 3, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcement_details`
--
ALTER TABLE `announcement_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `match_details`
--
ALTER TABLE `match_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_details`
--
ALTER TABLE `notification_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `participant_details`
--
ALTER TABLE `participant_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_details`
--
ALTER TABLE `referral_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rewarded_details`
--
ALTER TABLE `rewarded_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_details`
--
ALTER TABLE `room_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_about`
--
ALTER TABLE `tbl_about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_app_details`
--
ALTER TABLE `tbl_app_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contact`
--
ALTER TABLE `tbl_contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `tbl_image`
--
ALTER TABLE `tbl_image`
  ADD PRIMARY KEY (`img_id`);

--
-- Indexes for table `tbl_payouts`
--
ALTER TABLE `tbl_payouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_privacy_policy`
--
ALTER TABLE `tbl_privacy_policy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_push_notification`
--
ALTER TABLE `tbl_push_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rules`
--
ALTER TABLE `tbl_rules`
  ADD PRIMARY KEY (`rule_id`);

--
-- Indexes for table `tbl_terms_conditions`
--
ALTER TABLE `tbl_terms_conditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user_master`
--
ALTER TABLE `tbl_user_master`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `update_details`
--
ALTER TABLE `update_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcement_details`
--
ALTER TABLE `announcement_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `match_details`
--
ALTER TABLE `match_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;

--
-- AUTO_INCREMENT for table `notification_details`
--
ALTER TABLE `notification_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `participant_details`
--
ALTER TABLE `participant_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1057;

--
-- AUTO_INCREMENT for table `referral_details`
--
ALTER TABLE `referral_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `rewarded_details`
--
ALTER TABLE `rewarded_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `room_details`
--
ALTER TABLE `room_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;

--
-- AUTO_INCREMENT for table `tbl_about`
--
ALTER TABLE `tbl_about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_app_details`
--
ALTER TABLE `tbl_app_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_contact`
--
ALTER TABLE `tbl_contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_image`
--
ALTER TABLE `tbl_image`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_payouts`
--
ALTER TABLE `tbl_payouts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_privacy_policy`
--
ALTER TABLE `tbl_privacy_policy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_push_notification`
--
ALTER TABLE `tbl_push_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_rules`
--
ALTER TABLE `tbl_rules`
  MODIFY `rule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_terms_conditions`
--
ALTER TABLE `tbl_terms_conditions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- AUTO_INCREMENT for table `update_details`
--
ALTER TABLE `update_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=487;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
