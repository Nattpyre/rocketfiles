<ul>
  <li>
    <span class="fa-stack fa-lg"> <i class="fa fa-circle fa-stack-2x"></i> <i class="fa fa-cogs fa-stack-1x"></i>
    </span>
    <a href="/user/<?= \Helpers\Session::get('user_id'); ?>">Настройки</a>
  </li>
  <li>
    <span class="fa-stack fa-lg">
      <i class="fa fa-circle fa-stack-2x"></i>
      <i class="fa fa-usd fa-stack-1x"></i>
    </span>
    <a href="/user/premium">Премиум</a>
  </li>
  <li>
    <span class="fa-stack fa-lg">
      <i class="fa fa-circle fa-stack-2x"></i>
      <i class="fa fa-power-off fa-stack-1x"></i>
    </span>
    <a href="/user/logout">Выход</a>
  </li>
</ul>