INSERT INTO `editions` (`edition_id`, `edition_name`) VALUES
(1, '2001/2002'),
(2, '2002/2003'),
(3, '2003/2004'),
(4, '2004/2005'),
(5, '2005/2006'),
(6, '2006/2007'),
(7, '2007/2008'),
(9, '2008/2009'),
(10, '2009/2010');

INSERT INTO `template_settings` (`template_id` ,`template_name`) VALUES 
('1',  'simple');

INSERT INTO `users`  VALUES (NULL, `'admin', SHA1('admin1;'), 'Artur', 'Jewuła', 
null, null, 'arturro2@poczta.fm', 0, 'admin);

INSERT INTO `settings` VALUES 
(10, 1, 1048576, 'd.m.Y', 10, 1291158000, 1322607600, 1322607600, 1325977200); 