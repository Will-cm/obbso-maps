CREATE TABLE zonas(
    id int not null auto_increment,
    enlace TEXT NOT NULL,
    lat decimal(10,8) NULL,
    lng decimal(11,8) NULL,
	  direccion VARCHAR(255) NULL,
 PRIMARY KEY(id)
);