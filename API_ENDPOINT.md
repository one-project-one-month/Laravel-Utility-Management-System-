# 💻 API ENDPOINTS

This document lists all API endpoints for the Utility Management System project.

- **Local URL(Developement):** `http://127.0.0.1:8000/api/v1`
- **Production (Deployed):** `https://laravel-utility-management-system.aperturecampaign.com/api/documentation`

---

## 👮 Authentication APIs

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/login` | Login and get access token |
| POST | `/register` | Register a new user |
| POST | `/logout` | Logout current user |

---

## 🏬 Room APIs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/rooms` | List all rooms |
| GET | `/rooms/{id}` | Show room detail |
| POST | `/rooms` | New create room |
| PUT | `/rooms/{id}` | Update room |

---

## 👨 User APIs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/users` | List all users |
| GET | `/users/{id}` | Show user detail |
| POST | `/users` | New create user |
| PUT | `/users/{id}` | Update user |

---

## 👨‍🦱 Tenant APIs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/tenants` | List all tenants |
| GET | `/tenants/{id}` | Show tenant detail |
| POST | `/tenants` | New create tenant |
| PUT | `/tenants/{id}` | Update tenant |

---

## 👨‍👩‍👧 Occupant APIs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/occupants` | List all occupants |
| GET | `/occupants/{id}` | Show occupant detail |
| POST | `/occupants` | New create occupant |
| PUT | `/occupants/{id}` | Update occupant |

---

## 📜 Contract Type APIs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/contract-types` | List all contract types |
| GET | `/contract-types/{id}` | Show contract type detail |
| POST | `/contract-types` | New create contract type |
| PUT | `/contract-types/{id}` | Update contract type |

---

## 🖋️ Contract APIs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/contracts` | List all contracts |
| GET | `/contracts/{id}` | Show contract detail |
| POST | `/contracts` | New create contract |
| PUT | `/contracts/{id}` | Update contract |
| GET | `/tenants/{id}/contracts` | Show tenant's contract (Tenant only) |

---

## 💸 Bill APIs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/bills` | List all bills |
| GET | `/bills/{id}` | Show bill detail |
| POST | `/bills` | New create bill |
| GET | `/tenants/{id}/bills/latest` | Show tenant's lastest bill |
| GET | `/tenants/{id}/bills/history` | Show tenant's history bill |

---

## 📊 Total Unit APIs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/total-units` | List all total units |
| GET | `/total-units/{id}` | Show total unit detail |
| POST | `/total-units` | New create total unit |
| PUT | `/total-units/{id}` | Update total unit |

---

## 🧮 Invoice APIs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/invoices` | List all invoices |
| GET | `/invoices/{id}` | Show invoice detail |
| POST | `/invoices` | New create invoice |
| PUT | `/invoices/{id}` | Update invoice |
| GET | `/tenants/{id}/invoices/latest` | Show tenant's invoice lastest (Tenant Only) |
| GET | `/tenants/{id}/invoices/history` | Show tenant's invoice history (Tenant Only) |

---

## 🖨️ Receipt APIs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/receipts` | List all receipt |
| GET | `/receipts/{id}` | Show receipts detail |
| POST | `/receipts` | New create receipt |
| PUT | `/receipts/{id}` | Update receipt |
| GET | `/tenants/{id}/receipts/latest` | Show tenant's receipt lastest (Tenant Only) |
| GET | `/tenants/{id}/receipts/history` | Show tenant's receipt history (Tenant Only) |

---

## 📞 Customer Service APIs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/customer-services` | List all customer services |
| GET | `/customer-services/{id}` | Show customer service detail |
| PUT | `/customer-services/{id}` | Update customer service |
| POST | `/tenants/{id}/customer-services/create` | Create customer service (Tenant Only) |
| GET | `/tenants/{id}/customer-services/history/{status?}` | Show customer service history (Tenant Only) |

---
