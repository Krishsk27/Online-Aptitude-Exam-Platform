# Online-Aptitude-Exam-Platform
A secure, user-friendly aptitude exam site for colleges. Staff can manage questions; students can take exams and view results. Features include anti-cheating measures (fullscreen, no tab switch/copy), real-time leaderboard, and PHP Mailer integration for instant registration and score emails.

## 🌟 Features

- **Admin & Staff Access**: Upload, manage, and update question sets.
- **Student Portal**: Register, take exams, and instantly view results.
- **Anti-Cheating Techniques**:
  - Fullscreen enforcement
  - Tab switch detection and restriction
  - Copy-paste prevention
- **Email Notifications**:
  - Auto-generated emails using **PHP Mailer**
  - Sent on registration and after exam submission
- **Live Leaderboard**:
  - Real-time updates
  - Ranks students based on their scores

## 📌 Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Email Service**: PHP Mailer

## 🔐 Security Protocols

To ensure a fair testing environment:
- Fullscreen is mandatory during exams
- Switching tabs leads to warnings or termination
- Copy-paste and right-click are disabled during tests

## 📬 Email System

- When a user registers, a confirmation email is sent
- After completing an exam, the user's score is emailed automatically

## 📊 Dynamic Leaderboard

- Displays top performers instantly
- Automatically refreshes and updates scores in real-time

