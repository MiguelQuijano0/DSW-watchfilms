<?php
session_start();
/*Valida si la secion esta iniciada y de lo contrario lo redirige al login*/
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
   header("location: login.php");
   exit;
}
include 'connect.php';
/*Actualiza el carrito*/
if(isset($_POST['update_qty'])){

   $update_id = $_POST['cart_id'];
   $update_id = filter_var($update_id, FILTER_SANITIZE_STRING);
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);

   $update_cart = $conn->prepare("UPDATE `cart` SET qty = ? WHERE id = ?");
   $update_cart->execute([$qty, $update_id]);

   $success_message[] = 'Carrito actualizado';

}

/*Remueve items del carrito*/
if(isset($_POST['remove_item'])){

   $delete_id = $_POST['cart_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_item = $conn->prepare("SELECT * FROM `cart` WHERE id = ?");
   $verify_item->execute([$delete_id]);

   if($verify_item->rowCount() > 0){
      $delete_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
      $delete_item->execute([$delete_id]);
      $success_message[] = 'Articulo removido';
   }else{
      $warning_message[] = 'Articulo del carrito ya eliminado';
   }

}
/*Vacia todo el carrito*/
if(isset($_POST['delete_all'])){

   $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $verify_cart->execute([$user_id]);

   if($verify_cart->rowCount() > 0){
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);
      $success_message[] = 'Carito vacio';
   }else{
      $warning_message[] = 'Todos los aticulos fueron eliminados';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Carrito de compras</title>


   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="css/carrito.css">
   <link rel="stylesheet" href="css/estilos.css">
   <link rel="stylesheet" href="css/text.css">
</head>
<header>
   <img src="Whatchfilms-logo.png" width="100" height="70" alig="middle">
   <nav>
      <ul>
         <li><a href="index.php">Home</a></li>
			<li class="dropdown">
				<a href="#" class="dropbtn">Generos</a>
				<!-- Menu desplegabe de generos -->
				<div class="dropdown-content">
					<a href="Terror.html">Terror</a>
					<a href="comedia.html">Comedia</a>
					<a href="accion.html">Accion</a>
					<!-- Botones lateral de configuracion de sesion -->
					<li style="float:right"><a href="logout.php" class="btn btn-danger">Cierra la sesión</a></li>
					<li style="float:right"><a href="reset-password.php" class="btn btn-warning">Cambia tu contraseña</a></li>
				</div>
			</li>
			<li><a href="products.php">Tienda</a></li>
            <li><a href="about.html">Acerca de</a></li>
            <li><a href="contacto.html">Contacto</a></li>
		</ul>
	</nav>
</header>
<body>

<section class="products">

   <h1 class="heading">Carrito de compras</h1>

   <div class="box-container">
   <!-- muestra el contador de productos -->
   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
            $select_products->execute([$fetch_cart['product_id']]);
            $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
   ?>
   <!-- Muestra las peliculas segun el contenido que ste en la base de datos -->
   <form action="" method="POST" class="box">
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <img class="image" src="uploaded_files/<?= $fetch_product['image']; ?>" alt="">
      <h3 class="name"><?= $fetch_product['name']; ?></h3>
      <div class="flex">
         <span class="price"><?= $fetch_product['price']; ?> MX</span>
         <input type="number" name="qty" class="qty" max="99" min="1" maxlength="2" required value="<?= $fetch_cart['qty']; ?>">
         <button type="submit" name="update_qty" class="fas fa-edit"></button>
      </div>
      <p class="sub-total"><span>sub total :</span> <?= $sub_total = ($fetch_cart['qty'] * $fetch_product['price']); ?> MX</p>
      <!-- se agregan los botones del carrito -->
      <input type="submit" value="Eliminar del carrito" name="remove_item" class="delete-btn"  onclick="return confirm('¿elimnar del carrito?');">
   </form>
   <?php
   /*muestra el total*/
      $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty">Tu carrito esta vacio</p>';
   }
   ?>
   
   </div>

</section>

<section>

   <form action="" class="count-container" method="POST">
      <p>Total : <span><?= $grand_total; ?> MX</span></p>
      <input type="submit" value="Vaciar" name="delete_all" onclick="return confirm('Deseas vaciar el carrito');" class="inline-delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>">
   </form>

   <div class="flex-btn" style="margin-top: 30px;">
      <a href="products.php" class="inline-option-btn">Continuar comprando</a>
      <a href="#" class="inline-btn <?= ($grand_total > 1)?'':'disabled'; ?>">Pagar</a>
   </div>

</section>



















<!-- sweet alert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>

   document.querySelectorAll('input[type="number"]').forEach(inputNumbmer => {
      inputNumbmer.oninput = () =>{
         if(inputNumbmer.value.length > inputNumbmer.maxLength) inputNumbmer.value = inputNumbmer.value.slice(0, inputNumbmer.maxLength);
      }
   });

</script>
   
<?php include 'message.php'; ?>

</body>
</html>