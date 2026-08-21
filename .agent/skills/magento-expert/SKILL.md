---
name: magento-expert
description: Use this skill whenever the user asks to create Magento 2 modules, plugins, observers, layout XML, or refactor PHP code.
---

# Magento 2 & PHP Code Standards

## Guidelines
1. **Dependency Injection**: Always use constructor injection. NEVER use `ObjectManager` directly unless in a factory/proxy.
2. **PSR Standards**: Adhere strictly to **PSR-12** formatting and PHP 8+ strict type hints.
3. **Plugins over Observers**: Prefer `before`, `after`, or `around` plugins over event observers when extending core functionality.
4. **Declarative Schema**: Always use `db_schema.xml` for database tables instead of legacy setup scripts.
5. **Modular XML Layout**: Keep layout XML organized under `view/frontend/layout/` or `view/adminhtml/layout/`.
