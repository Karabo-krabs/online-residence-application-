create database InformationSystems;

use InformationSystems;

create table studentRegistration(
	StudentID int auto_increment not null primary key,
	studentName varchar(25) not null,
    studentSurname varchar(30) not null,
    studentNumber int unique not null,
    gender varchar(10) not null,
    appStatus varchar(50) not null,
    accommodation varchar(50) not null
);
create table Student(
	StudentID int auto_increment not null primary key,
	studentName varchar(25) not null,
    studentSurname varchar(30) not null,
    studentNumber int unique not null
	);

create table Moroka(
	MorokaID int auto_increment not null primary key,
    sharingRooms int not null default 3,
    sharing_accepted int not null,
    address varchar(255), 
    image longblob
    );
    
create table Umnandi(
	UmnandiID int auto_increment not null primary key,
    sharingRooms int not null default 3,
    sharing_accepted int not null,
    address varchar(255), 
    image longblob
    );
    
create table Hannetjie(
	HannetjieID int auto_increment not null primary key,
    sharingRooms int not null default 3,
    sharing_accepted int not null,
    address varchar(255), 
    image longblob
    );
    
create table Mhudi(
	MhudiID int auto_increment not null primary key,
    sharingRooms int not null default 3,
    sharing_accepted int not null,
    address varchar(255), 
    image longblob
    );
    
create table Rathaga(
	RathagaID int auto_increment not null primary key,
    sharingRooms int not null default 3,
    sharing_accepted int not null,
    address varchar(255), 
    image longblob
    );
    
create table Tauana(
	TauanaID int auto_increment not null primary key,
    rooms int not null default 3,
    sharing_accepted int not null,
    address varchar(255), 
    image longblob
    );
    
create table Residences(
	residenceID int not null auto_increment primary key,
	residenceName varchar(25)not null,
    totalRooms int not null,
    StudentsApplied int not null default 0,
    studentsAccepted int not null default 0,
    address varchar(255),
    images longblob not null
    );
insert into residences(residenceName, totalRooms, address, images) values 
('Moroka', 3 , "26 Scanlan street, Labrahm, Kimberley, 8300", "moroka.jpg"),
('Umnandi',3 , '29 Jacobus Smit Avenue, New Park, Kimberley, 8301', 'Umnandi.jpg'),
('Hannetjie', 5, '29 Jacobus Smit Avenue, New Park, Kimberley, 8301', 'Hannetjie.jpg'),
("Mhudi", 6, '124 Du Toitspan rd, Civic Centre, Kimberley, 8300', 'Mhudi.jpg'),
('Rathaga', 5, '35 Edwards rd, Kimberley, 8300', 'Rathaga.jpg'),
("Tauana", 5, '29 Jacobus Smit Avenue, New Park, Kimberley, 8301', 'Tauana.jpg');


select * from Residences;
#mhudi 124 Du Toitspan rd, Civic Centre, Kimberley, 8300
#Rathaga 35 Edwards rd, Kimberley, 8300
#South Camp: 29 Jacobus Smit Avenue, New Park, Kimberley, 8301
    
    
create table employee(
	employeeID int auto_increment not null primary key,
	employeeName varchar(25) not null,
    employeeSurname varchar(30) not null,
    employeeNumber varchar(25) unique not null
    );
    
insert into employee(employeeName, employeeSurname, employeeNumber) values("Lerato", "Modika", "ep202213141"),
																			("Krabs", "Manjiya", "ep202103317"),
                                                                            ("Mpho", "Reiners", "ep202215553"),
                                                                            ("Tumelo", "Sekoboane","ep202114560");
select * from employee;
create table admin(
	adminID int auto_increment not null primary key,
	adminName varchar(25) not null,
    adminSurname varchar(30) not null,
    adminNumber varchar(25) unique not null,
    password varchar(255)
    );

alter table Student
add studentPassword varchar(255);
select * from admin;