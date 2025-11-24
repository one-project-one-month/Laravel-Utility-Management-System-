# 💡 Utility Management System

## 🔍 စနစ်အ‌ကြောင်း

**Utility Management System** သည် လျှပ်စစ်၊ ရေဘီလ်၊ အခန်းငှား၊ ပစ္စည်းအငှား(‌ဥပမာ- ရေခဲသေတ္တာ၊ လေအေးပေးစက်) စသည်တို့ကို စနစ်တကျ စီမံခန့်ခွဲနိုင်အောင် ဖန်တီးထားသော Web Application ဖြစ်ပါသည်။ ဒီစနစ်ကို **Laravel Framework** ဖြင့် တည်ဆောက်ထားပြီး **API သီးသန့်ပိုင်း** ဖြစ်ပါသည်။

---

## 🛠️ အသုံးပြုထားသော Technology များ

| Category       | Technology                   |
| -------------- | ---------------------------- |
| Framework      | Laravel 12                   |
| Language       | PHP 8.3+                     |
| Database       | PostgreSQL                   |
| Authentication | Laravel Sanctum(Token-based) |
| API Format     | RESTful                      |

---

## 🎉 ပါ၀င်သော feature များ

### 👨‍🦱 အသုံးပြုသူ စီမံခန့်ခွဲမှု

-   အသုံးပြုသူများ (Admin / Staff / Tenant) ကို စုစည်း စီမံနိုင်ခြင်း။
-   အသုံးပြုသူ အသစ်များ Create / Update လုပ်နိုင်ခြင်း။
-   Role နှင့် Permission များ ချမှတ်နိုင်ခြင်း။

---

### 📊 မီတာအချက်အလက်

-   မီတာအမျိုးအစားများ (Electric / Water / Room / General) များ စီမံနိုင်ခြင်း။
-   မီတာ Usage ကို System မှ တွက်ချက်ပေးခြင်း။

---

## 📜 စာချုပ်စီမံခန့်ခွဲမှု

-   Admin / Staff များမှ စာချုပ်များကို Create / Update / Delete စီမံနိုင်ခြင်း။
-   Tenant မှ စာချုပ်အချက်အလက်များ ကြည့်ရှုနိုင်ခြင်း။

---

### 📞 Customer Service

-   Tenant များအတွက် တိုက်ခန်းများ၏ ပြဿနာများကို ပေးပို့ခြင်း။
-   Admin / Staff များမှ ဖြေရှင်းပြီးသော ပြဿနာများကို ပြန်လည်ပေးပို့ခြင်း။
-   Tenant များမှ အစီရင်ခံစာ history များ ကြည့်နိုင်ခြင်း။

---

## 📂 Folder Structure

```
app/
 |--Enums/
 |--Http/
 |  |--Controllers/
 |  |--Helpers/
 |  |--Jobs/
 |  |--Middleware/
 |  |--Services/
 |--Models/
 database/
 |--factories/
 |--migrations/
 |--seeders/
 routes/
 |--api.php

```

---

## 📦 Installation Guide

```bash
git clone https://github.com/Laravel-Utility-Management-System-
```
```
cd Laravel-Utility-Management-System-
```
```
composer install
```
```
cp .env.example .env
```
```
php artisan key:generate
```
```
php artisan migrate --seed
```
```
php artisan serve
```

---

<table>
 <thead>
  <tr>
   <th colspan="12">Contributors</th>
  </tr>
 </thead>
    <tbody>
        <tr>
            <td><a href="https://github.com/KaungSettThu1873"><img src="https://github.com/KaungSettThu1873.png" width="60px;"/></a></td>
            <td><a href="https://github.com/hteinlinaungt4"><img src="https://github.com/hteinlinaungt4.png" width="60px;"/></a></td>
           <td><a href="https://github.com/404j361"><img src="https://github.com/404j361.png" width="60px;"/></a></td>
          <td><a href="https://github.com/naingaunglwin-dev"><img src="https://github.com/naingaunglwin-dev.png" width="60px;"/></a></td>
            <td><a href="https://github.com/winminthantdev"><img src="https://github.com/winminthantdev.png" width="60px;"/></a></td>
            <td><a href="https://github.com/AungMinKo-tech"><img src="https://github.com/AungMinKo-tech.png" width="60px;"/></a></td>
           <td><a href="https://github.com/Nyan-MinHtet"><img src="https://github.com/Nyan-MinHtet.png" width="60px;"/></a></td>
           <td><a href="https://github.com/AKThu"><img src="https://github.com/AKThu.png" width="60px;"/></a></td>
           <td><a href="https://github.com/MgKhai"><img src="https://github.com/MgKhai.png" width="60px;"/></a></td>
           <td><a href="https://github.com/Moehtet-hlaing"><img src="https://github.com/Moehtet-hlaing.png" width="60px;"/></a></td>
           <td><a href="https://github.com/crankygrey"><img src="https://github.com/crankygrey.png" width="60px;"/></a></td>
           <td><a href="https://github.com/TueTu"><img src="https://github.com/TueTu.png" width="60px;"/></a></td>
           <td><a href="https://github.com/MadThura"><img src="https://github.com/MadThura.png" width="60px;"/></a></td>
           <td><a href="https://github.com/PhonePyaeKo"><img src="https://github.com/PhonePyaeKo.png" width="60px;"/></a></td>
           <td><a href="https://github.com/PhyoHtetKyaw-Dev"><img src="https://github.com/PhyoHtetKyaw-Dev.png" width="60px;"/></a></td>
           <td><a href="https://github.com/MyatThinzar1259"><img src="https://github.com/MyatThinzar1259.png" width="60px;"/></a></td>
        </tr>
    </tbody>
</table>


<!-- Security scan triggered at 2025-11-24 23:29:29 -->