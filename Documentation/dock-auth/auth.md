---
marp: false
theme: gaia
_class: lead
backgroundColor: #000
color: #d4af37
fontFamily: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif

---

# Arcivura: AI-Job Board Ecosystem
## Authentication & Authorization Protocol (V3)
**April 2026 Standards**

---

# 1. Super Admin Flow (The Controller)
- **Entry Point:** Database Seeder Only (No Registration).
- **Access Level:** Absolute System Control.
- **Protocol:** 1. Direct Login (Email/Password).
    2. Multi-Factor Authentication (System Alert).
    3. Access Dashboard & User Management.

---

# 2. Admin & Staff Flow (Internal)
- **Registration:** Managed by Super Admin.
- **Verification:** - Initial Login via Temporary Password.
    - Forced Password Reset on first entry.
    - **OTP Protocol:** Required for every new session/IP change.
- **Permissions:** CRUD on Keywords, Categories, and Moderation.

---

# 3. Seeker Flow (The AI Hub)
- **Dual Entry:** Phone OR Email.
- **Registration Process:**
    1. Input Identifier -> Trigger OTP.
    2. Verify OTP -> Create Profile.
- **Social Integration:** Google OAuth 2.0.
- **Security:** Session-based via Laravel Sanctum.

---

# 4. Technical Stack (The Engine)
- **Auth Core:** Laravel Sanctum (Headless).
- **Session Management:** Secure HttpOnly Cookies.
- **AI Logic Layer:** Linked to `user_id` for Resume Parsing.
- **Audit Trail:** Every login attempt logged in `activity_logs`.

---

# 5. Security & Scaling
- **Rate Limiting:** Protects OTP endpoints from brute force.
- **Scalability:** Built for Multi-App Architecture (Admin Panel + Seeker SPA).
- **Data Integrity:** UUIDs for all User references.