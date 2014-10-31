DROP TABLE IF EXISTS `chitiet`;
CREATE TABLE IF NOT EXISTS `chitiet` (
  `mahd` int(11) NOT NULL,
  `masp` int(11) NOT NULL,
  `gia` int(11) NOT NULL,
  `chietkhau` int(11) NOT NULL,
  PRIMARY KEY (`mahd`,`masp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `hoadon`;
CREATE TABLE IF NOT EXISTS `hoadon` (
  `mahd` int(11) NOT NULL AUTO_INCREMENT,
  `total` int(11) NOT NULL,
  `chietkhau` int(11) NOT NULL,
  `ngaylap` date NOT NULL,
  `nguoilap` varchar(50) NOT NULL,
  `khachhang` varchar(50) NOT NULL,
  `hople` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`mahd`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `phieuchi`;
CREATE TABLE IF NOT EXISTS `phieuchi` (
  `maso` int(11) NOT NULL,
  `ngaylap` date NOT NULL,
  `noidung` text COLLATE utf8_unicode_ci NOT NULL,
  `giatri` int(11) NOT NULL,
  `loai` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`maso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `masp` int(11) NOT NULL AUTO_INCREMENT,
  `tensp` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `giamacdinh` int(11) NOT NULL,
  `chietkhau` int(11) NOT NULL DEFAULT '0',
  `isValid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`masp`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `isValid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
# The only data available
INSERT INTO `user` VALUES('admin','21232f297a57a5a743894a0e4a801fc3',1);