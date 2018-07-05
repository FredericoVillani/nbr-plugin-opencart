## Вступ
Криптовалюта — це сучасний децентралізований платіжний інструмент, який має надзвичайно широкі можливості та знімає багато обмежень, притаманних класичним платіжним системам. Так, до прикладу, він дозволяє виконувати платежі анонімно та безпечно. Також не має жодних географічних обмежень чи зобов'язань, а це дозволяє мати доступ та виконувати платежі з будь якого місця. У якості середовища подібні системі використовують мережу “Інтернет”.

## Особливості та специфіка
У рамках платіжного засобу в інтернет-магазинах криптовалюта має деякі особливості, притаманні лишень цьому платіжному засобу. Спроба інтегрувати її в існуючі системи для продажу змусила шукати рішення для зручного та ефективного використання користувачами. Перш за все, мова йде про так званий період підтвердження, що є типовим для будь-якої криптовалютної платіжної системи, але відсутній як визначення для класичних платіжних засобів. У даному випадку ПЗ бере цю задачу на себе, виконуючи всі перевірки у фоновому автоматичному режимі, не змушуючи користувача очікувати перед вікном браузера на завершення платежу. Після підтвердження платежу, система сама змінить статус замовлення та відправить відповідне повідомлення для електрону адресу. Також на сторінці оплати можна спостерігати проходження підтверджень.

## Платіжний плагін
У цій статті піде мова про перший платіжний плагін для національної української обмінної системи “Карбованець” (“Карбо”). Розширення написано для доволі ранньої версії платформи OpenCart. Це було зроблено для збереження зворотної сумісності з іншими версіями CMS, тому можна очікувати релізи під більш свіжі версії. Наразі наш магазин зараз працює саме на цій редакції.
Система працює повністю в автоматичному режимі та не вимагає будь-яких втручань у свою роботу. Основне завдання, яке вирішує дане розширення, це автоматична обробка платежів. Під час створення нового замовлення, користувачу буде додано до вибору новий метод оплати “Карбо”, де буде надана вся необхідна інформація щодо подальшої роботи для успішного виконання оплати. Після чого, користувача буде перенаправлено на спеціальну сторінку, де можна виконати платіж та спостерігати за його виконанням. Також у цей самий час відбудеться генерація замовлення та надсилання реквізитів для оплати на контактну адресу покупця. Після оплати та підтвердження платежу на адресу електронної адреси користувача буде надіслано інформації про зміну статуту замовлення та причину зміни (отримано платіж). На цьому покупцеві залишається лиш дочекатись відповідного телефонного дзвінка від менеджера для узгодження доставки, якщо це потрібно. У цей час кошти за замовлення уже на гаманці магазину. Тому від тепер залишається лиш обробити замовлення.

## Технічні особливості
Для роботи плагіна необхідна активна служба для обробки платежів. На разі, існує три види для подібного рішення: класичний консольний гаманець SimpleWallet, що запущений у режимі сервісу; WalletD — профільний сервіс віртуальних гаманців; платіжний шлюз від розробників KRB (рекомендований варіант). З усіма цими сервісами плагін спроможний ефективно працювати.
Для роботи платіжного процесора плагіна потрібна можливість виконувати фонові операції. Це здійснюється за допомогою широковідомого рішення на базі CRON, тому на хостингу, де працює магазин, повинна бути доступна ця опція. Також, якщо використовується саме віртуальний хостінг, то у будь якому випадку знадобиться можливість виконувати запити до зовнішніх сервісів, що не завжди доступно, тому потрібно обов’язково переконатись у такій можливості у служби підтримки. У разі неможливості здійснювати подібні запити, плагін не зможе працювати.

## Налаштування платіжного сервісу
Для роботи плагіна потрібен активно діючий сервіс для виконання платіжних операції. Першим розглянемо платіжний сервіс від розробників KRB, так як для рішення завдання він підходить як найбільш зручний та швидкий. Користуватись ним надзвичайно просто. Потрібно лиш зайти на сайт сервісу, створити новий гаманець та ретельно записати всі дані вашого гаманця. Також слід звернути увагу на те, що даний сервіс не пропонує повноцінно діючі гаманці, тому для доступу до коштів потрібно встановити відповідні ключі у вашу програму-гаманець. Лишень у цьому випадку буде можливість розпоряджатись коштами, що надходять за замовлення.
Можна використовувати консервативний та більш класичний гаманець Simplewallet, запустивши його у режимі сервісу. Для його роботи знадобиться активно діюча та доступна нода криптовалюти KRB, а також комп’ютер з виходом у мережу чи віддалений сервер для розміщення сервісу гаманця.

## Встановлення плагіну
Поточна версія плагіну установлюється дуже просто. Для цього необхідно розпакувати дистрибутив розширення у кореневу директорію магазину. Далі слід перейти у розділ з додатками, де обрати підрозділ з платіжними засобами та знайти у переліку “Niobio”. Після встановлення, переходимо у налаштування плагіну, де бачимо перелік параметрів. Найбільш важливі, це: адреса (адреса гаманця магазину), хост та порт платіжного сервісу. У першому параметрі вказуємо адресу згенерованого гаманця. Якщо обрано для роботи платіжний сервіс від розробників KRB, то поля з налаштунками підключення можна не чіпати, адже вони зазначені по замовчуванню. Потім слід увімкнути плагін, обравши відповідний пункт. Якщо ви хочете використовувати цей спосіб оплати лиш для деяких регіонів, це також можна налаштувати. При необхідності, вкажіть бажану кількість підтверджень (рекомендований діапазон значень: 6-10).
Для роботи платіжного процесора необхідно налаштувати CRON, вказавши рядок до виконання з часом 5 хвилин (домен необхідно вказати свій): wget -O /dev/null -q http://krb-shop.pp.ua/index.php?route=p ... karbo/cron .