</div>
<div class="row" style="margin-top: 5%;">
    <div class="col-md-12 footer" style="padding: 10px">
        Usuário logado: 
        <b><?php
        echo $this->session->userdata["logged_user"]->username;
        ?></b>
    </div>
</div>

</body>
</html>