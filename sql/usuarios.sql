-- Usuarios

SELECT * FROM users ORDER BY id DESC LIMIT 10;

alter table users
add column api_token VARCHAR(150) null after PASSWORD,
add column celular VARCHAR(150) null after api_token;

alter table users
add column avatar text null after celular;

alter table users
add column avatar_40 text null after avatar;

