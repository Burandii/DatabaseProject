drop table enduser cascade constraints;
drop table usersession cascade constraints;
drop table taken cascade constraints;
drop table coursesection cascade constraints;
drop table course cascade constraints;
--drop table prerequisite cascade constraints;

create table enduser(
	userid varchar2(8) primary key, --0
	password varchar2(12) not null, --1
	student_flag number(1,0),--2
	age number(2), --3
	fname varchar2(15), --4
	lname varchar2(15), --5
	status varchar2(1), --6 
	street varchar2(20), --7
	city varchar2(15), --8
	st_state varchar2(2), --9
	zip integer, --10
	st_type varchar2(1), --11 undergrad
	admin_flag number(1,0) --12
);

create table usersession(
	sessionid varchar2(32) primary key,
	userid varchar2(8) references enduser,
	sessiondate date
);
--added prereq's here
create table course(
	cno varchar2(10) primary key,
	description varchar2(60),
	credit_hrs number(1),
	title varchar2(25) not null unique,
	prereq_needed varchar2(10),
	foreign key (prereq_needed) references course (cno)
);

create table coursesection(
	sec_id varchar2(6) primary key,
	course_no varchar2(10) references course,
	capacity number(3),
	students_enrolled number(3),
	days_of_week varchar2(5),
	timeslot varchar2(11),
	semester_offered varchar2(11),
	enroll_deadline date
);
--added a flag to this
create table taken(
	sid varchar2(8) references enduser,
	sec_id varchar2(6) references coursesection,
	grade number(4,1),
	enroll_flag number(1,0),
	primary key (sid, sec_id)
);

create table countusers(
	total_users number primary key
);
--updates the user total whenever a new user is added
--keeps a count even for when users are deleted from system
create or replace trigger TotalUsers
after insert on enduser
begin
	update countusers set total_users = total_users+1;
end;
/

--checks if the deadline is passed
create or replace procedure check_deadline
	(my_sec in varchar2, my_error out varchar2)
	is
	my_date date := CURRENT_DATE;
	my_enrolldeadline date;
begin
	select enroll_deadline into my_enrolldeadline from coursesection 
		where sec_id = my_sec;

	IF my_enrolldeadline < my_date THEN
		my_error := '0';
	ELSE
		my_error := '1';
	END IF;
end;
/

create or replace view users as
select userid, student_flag, admin_flag
from enduser;

--long duration transaction
create or replace procedure new_student_id
	(my_fname in varchar2, my_lname in varchar2)
	is
	my_id varchar2(8);
	my_count number;
begin
	LOCK TABLE enduser in ROW EXCLUSIVE MODE NOWAIT;
	select * into my_count from countusers;
	my_count := my_count + 1;
	my_id := SUBSTR(my_fname,1,1) || SUBSTR(my_lname,1,1)
		|| TO_CHAR(my_count,'FM000000');
	insert into enduser (userid,password, fname, lname) values (my_id, 'bronchos', my_fname, my_lname); 
	
	commit;
end;
/

