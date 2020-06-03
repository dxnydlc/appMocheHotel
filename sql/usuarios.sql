-- Usuarios

SELECT * FROM users ORDER BY id DESC LIMIT 10;

alter table users
add column api_token VARCHAR(150) null after PASSWORD,
add column celular VARCHAR(150) null after api_token;





