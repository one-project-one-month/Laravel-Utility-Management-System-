# Contributing Guidelines

Thank you for considering contributing to this project! We appreciate your time and effort in helping us improve.

---

## 🚀 Feature & Branching Name

**Use the following format for feature names:**

- `feat:[description]` - For new feature / functionality
- `fix:[description]` - For bug fix
- `hotfix:[description]` - For+ critical production fix
- `chore:[description]` - For maintenance / tooling / no code change
- `docs:[description]` - For documentation change
- `style:[description]` - For formatting / style / lint
- `refactor:[description]` - For code refactor (no feature / no bug)
- `test:[description]` - For add / modify tests

**Example**

- `feat:user_add`
- `fix:tenant_seeder`
- `docs:update_API_documentation`
- `refactor:user_api`

**Use the following format for branch names:**

- `feat/[description]` - For feature    
-  `fix/[description]` - For bugfix
-  `hotfix/[description]` - For hotfix
-  `chore/[description]` - For chore
-  `release/[description]` - For release
- `test/[description]` - For Test

**Example**

- `feat/all-migration-create`
- `fix/login-validation`
- `hotfix/payment-error`
- `chore/update-composer`
- `release/v1.0.0`
- `test/user-api`

---

## 📂 File Naming Convention

Use **PascalCase** for all files:

**Examples:**

- `UserController.php`
- `BillController.php`

---

## 📶 Database Guidelines

### 🖋️ Naming Conventions

- Use `snake_case` for all database column names.
  💡Example: `room_no`, `rental_fee`
- Use `PascalCase` for model names.
  💡Example: `ContractType.php`, `TotalUnit.php`
- Foreign keys should include `_id` suffix.
  💡Example: `user_id`, `room_id`

---

## 📝 API Response Format

All API responses should follow this consistent format using the `successResponse` helper:

```php
// Success Response
{
  "success": true,
  "message": "successful",
  "content": { /* data */ },
  "status": 200
}

// Error Response
{
  "success": false,
  "message": "Error description",
  "status": 400
}

//Usage in controller
use App\Http\Helpers\ApiResponse;

return $this->successResponse('User created successfully', new UserResource($user), 201);
```

---

## 🤝 Pull Requests

- Keep PRs small and focused on a single feature or fix.
- Link related issues in the PR description.
- Request at least one reviewer before merging.
- Ensure all tests pass before submitting.
- Update documentation if needed.
