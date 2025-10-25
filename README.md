Тестовое задание
Приложение для работы с балансом пользователей (PHP / Laravel)

_В проекте предусмотрено заполнение базы тестовыми данными через php artisan db:seed_

**1. Начисление средств пользователю**
_POST /api/deposit_

- Реализация в контроллере BalanceController.php, метод deposit();
- Предусмотрена валидация входных значений;
- SQL транзакция описана в UserBalanceService.php, методы deposit() и depositTransaction();
- Предусмотрено логирование операции в журнал;

**2. Списание средств**
_POST /api/withdraw_
- Реализация в контроллере BalanceController.php, метод withdraw();
- Предусмотрена валидация входных значений;
- SQL транзакция описана в UserBalanceService.php, методы withdraw() и withdrawTransaction();
- Предусмотрено логирование операции в журнал;

**3. Перевод между пользователями**
_POST /api/transfer_
- Реализация в контроллере BalanceController.php, метод transfer();
- Предусмотрена валидация входных значений;
- SQL транзакция описана в UserBalanceService.php, метод transfer(); также используются транзакции из пунктов 1-2;
- Предусмотрено логирование операции в журнал;

**4. Получение баланса пользователя**
_GET /api/balance/{user_id}_
- Реализация в контроллере BalanceController.php, метод get и в сервисе UserBalanceService.php, метод getUserBalance();
