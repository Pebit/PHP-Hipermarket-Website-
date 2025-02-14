USE market_jonsi;

-- insert new purchase permission
INSERT INTO permissions (permission_id, name) VALUES (9, 'purchase_item');

-- add permmision to admin(role_id=1) and user(role_id=2)
INSERT INTO roles_permissions (role_id, permission_id) VALUES (1, 9);
INSERT INTO roles_permissions (role_id, permission_id) VALUES (2, 9);

INSERT INTO migrations (name) VALUES ('purchase_permissions_3');