<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Trippal - Login</title>
        <?php
        echo $this->Html->css('/backend/vendors/bootstrap/dist/css/bootstrap.min');
        echo $this->Html->css('/backend/vendors/font-awesome/css/font-awesome.min');
        echo $this->Html->css('/backend/vendors/animate.css/animate.min');
        echo $this->Html->css('/backend/build/css/custom.min');
        echo $this->Html->css('/backend/css/style');
        
        echo $this->fetch('meta');
        echo $this->fetch('css');
        ?>
    </head>

    <body class="login">
        <div>
            <a class="hiddenanchor" id="signup"></a>
            <a class="hiddenanchor" id="signin"></a>

            <div class="login_wrapper">
                <div class="animate form login_form">
                    <section class="login_content">
                        <?php echo $this->fetch('content'); ?>                        
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>
