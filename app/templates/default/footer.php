<div class="advantages clearfix">
  <div class="advantage-block">
    <h3><i class="fa fa-thumbs-o-up"></i>Безопасность</h3>
    <p>
      Все ваши файлы хорошо защищены и никто не сможет получить к ним доступ без вашего ведома. Вы в надежных руках!
    </p>
  </div>
  <div class="advantage-block">
    <h3><i class="fa fa-download"></i>Высокая скорость</h3>
    <p>
      Вы всегда можете расчитывать на то, что файлы будут скачаны с максимально возможной скоростью, без задержек и потери связи!
    </p>
  </div>
  <div class="advantage-block">
    <h3><i class="fa fa-paper-plane-o"></i>Отзывчивость</h3>
    <p>
      Если у вас возникнут проблемы с сайтом, то вы всегда можете связаться с администрацией и получить быстрое решение проблемы!
    </p>
  </div>
</div>
</div>
</main>
<footer>
<div class="bottom-menu center-block clearfix">
<div class="bottom-menu-block">
  <h3>Соц. сети</h3>
  <ul>
    <li>
      <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-vk fa-stack-1x"></i>
      </span>
      <a href="http://vk.com/nattpyre" target="_blank">Страница Вконтакте</a>
    </li>
    <li>
      <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-facebook fa-stack-1x"></i>
      </span>
      <a href="https://www.facebook.com/profile.php?id=100005043488592" target="_blank">Страница Facebook</a>
    </li>
    <li>
      <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-youtube fa-stack-1x"></i>
      </span>
      <a href="https://www.youtube.com/channel/UCR5Oh4vGYKANKclc4KgPk8g" target="_blank">Канал на Youtube</a>
    </li>
  </ul>
</div>
<div class="bottom-menu-block">
  <h3>Аккаунт</h3>

  <?php
    if(!empty($_SESSION['rf_user'])) {
      require 'footer_logged.php';
    } else {
       require 'footer_notlogged.php';
    }
  ?>

</div>
<div class="bottom-menu-block">
  <h3>Контакты</h3>
  <ul>
    <li>
      <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-info fa-stack-1x"></i>
      </span>
      <a href="/about">Об авторе</a>
    </li>
    <li>
      <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-skype fa-stack-1x"></i>
      </span>
      <a href="skype:rovnatt?chat">rovnatt</a>
    </li>
    <li>
      <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-envelope-o fa-stack-1x"></i>
      </span>
      <a href="mailto:nattpyre@gmail.com">nattpyre@gmail.com</a>
    </li>
  </ul>
</div>
<div class="bottom-menu-block">
  <ul>
    <h3>Поддержка</h3>
    <li>
      <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-question fa-stack-1x"></i>
      </span>
      <a href="/faq">FAQ</a>
    </li>
    <li>
      <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-exclamation fa-stack-1x"></i>
      </span>
      <a href="/terms">Условия использования</a>
    </li>
    <li>
      <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-user-secret fa-stack-1x"></i>
      </span>
      <a href="/privacy">Политика конфиденциальности</a>
    </li>
  </ul>
</div>
</div>
<div class="info-footer">
<div class="version">
  <span>Rocket Files ver.1.0.0</span>
</div>
<div class="copyrights">
  <span>
    Created by <span>N</span>attpyre
  </span>
  <br>
  <span>All rights reserved © 2015</span>
</div>
</div>
</footer>
</body>
</html>