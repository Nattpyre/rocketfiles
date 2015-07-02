<main>
      <div class="container">
          <div class="main-block center-block">
            <div class="upload-toggle center-block">
              <ul class="nav nav-pills" role="tablist">
                <li class="active"><a href="#main_upload" aria-controls="main_upload" role="pill" data-toggle="pill">Обычная загрузка</a></li>
                <li><a href="#remote_upload" aria-controls="remote_upload" role="pill" data-toggle="pill">Удаленная загрузка</a></li>
              </ul>
            </div>
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane fade in active" id="main_upload">
              <form class="main-file-upload" enctype="multipart/form-data" method="POST" action="/files">
                <span><h3>Максимальный размер файла: 50 мегабайт.</h3></span>
              <div class="main-upload-progress"><span class="upload-label"></span></div>
                <span class="input-group-btn">
                    <input type="text" class="form-control input-field" readonly>
                    <span class="btn btn-primary upload-file">
                        Обзор <input type="file" name="uploadFile">
                    </span>
                </span>
                <span class="main-upload-error"></span>
                <div class="terms-check center-block">
                  <input type="checkbox" id="main-terms" name="terms">
                  <label for="main-terms">Я прочитал и принимаю <a href="/terms">условия пользования</a></label>
                </div>
                <div class="upload-button center-block">
                  <button type="submit" class="btn btn-warning">Загрузить</button>
                </div>
              </form>
              </div>
              <div role="tabpanel" class="tab-pane fade" id="remote_upload">
              <form class="remote-file-upload" method="POST" action="/files">
              <span><h3>Максимальный размер файла: 50 мегабайт.</h3></span>
                <div class="remote-upload-field">
                  <input type="url" name="remote_url" placeholder="Укажите ссылку на файл">
                </div>
                <div class="remote-upload-progress"><span class="upload-label"></span></div>
                <span class="remote-upload-error"></span>
                <div class="terms-check center-block">
                  <input type="checkbox" id="remote-terms" name="terms">
                  <label for="remote-terms">Я прочитал и принимаю <a href="/terms">условия пользования</a></label>
                </div>
                <div class="upload-button center-block">
                  <button type="submit" class="btn btn-warning">Загрузить</button>
                </div>
              </form>
            </div>
          </div>
          </div>