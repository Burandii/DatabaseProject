insert into enduser values ('ce000001', 'pass', '1', '23', 'Corey', 'Elton', '0', '123 Main St', 
	'Chandler', 'OK', '74834', 'U', '1');

insert into totalusers values ('0');

insert into course values ('CS101', 'Students will learn basic programming skills', 
	'3', 'Intro to Programming', 'CS101');
insert into course values ('CS102', 'Students will take the next step in learning how to program', 
	'3', 'Programming I', 'CS101');
insert into course values ('CS999', 'Students will learn to develop an enterprise level program', 
	'3', 'CS Capstone', 'CS102');
insert into course values ('MATH101', 'Students will learn basic algebra skills', 
	'3', 'College Algebra', 'MATH101');
insert into course values ('ENG101', 'Students will learn how to write essays', 
	'3', 'English Comp I', 'ENG101');
insert into course values ('ENG102', 'Students will learn how to write better essays', 
	'3', 'English Comp II', 'ENG101');

insert into coursesection values ('100001','CS101',  '3', '0', 'MWF', '1:00-1:50',
	'Spring 2017', '31-DEC-2016');
insert into coursesection values ('200001', 'CS102','2', '0', 'TR', '3:00-4:20',
	'Spring 2017', '31-DEC-2016');
insert into coursesection values ('123456','MATH101',  '5', '0', 'MTWR', '8:00-10:00',
	'Fall 2016', '30-AUG-2016');
insert into coursesection values ('999999','CS999', '1', '0', 'TR', '8:00-9:20',
	'Summer 2017', '30-APR-2017');	
insert into coursesection values ('112233','ENG101',  '5', '0', 'TR', '8:00-9:20',
	'Spring 2017', '31-DEC-2016');	
insert into coursesection values ('445566','ENG102', '3', '0', 'TR', '8:00-9:20',
	'Summer 2017', '30-APR-2017');	

insert into taken values ('ce000001', '100001', '4.0', '1');
insert into taken values ('ce000001', '200001', '4.0', '1');
insert into taken values ('ce000001', '123456', '2.5', '1');
insert into taken values ('jc000011', '123456', '3.5', '1');

insert into prerequisite values ('100001', '200001');
insert into prerequisite values ('200001', '999999');
insert into prerequisite values ('112233', '445566');