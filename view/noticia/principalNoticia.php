
		<div class="container content">
			<div class="row">
				<div class="col-md-12">
					<!---Si es moderador o superior--->
					<span id="panelAdminMos">
						<a onclick="mostrar_panel_creacion()" class="btn glyphicon glyphicon-menu-down"></a>
					</span>
					<div id="panelAdmin" class="row botonesNoticia">
						<div class="col-md-12">
							<h4>Panel de administraci&oacute;n de noticia</h4>
							<a href="crearNoticia.html" class="btn btn-default">Crear</a>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="ocultar_panel_creacion()">&times;</button>
						</div>
					</div>
					<!---->
					<div class="row">
						<div class="col-md-9 bloqNot" id="bloqNot">
							<div class="page-header encabezado">
								NOTICIAS
							</div>
							<!--Repetir en bucle-->
							<div class="pantNot">
								<div class="tituloNot">
									<h3><a href="#">
										Actualidad ROM: CM 13 Release 1, Android N con forma libre de ventanas, SuperSU 2.70 y mucho más
									</a></h3>
								</div>
								<div class="imgNot">
									<img src="./img/imgNot1.jpg" alt="imagen noticia" class="img-responsive">
								</div>
								<div class="datosNot">
									<div class="row">
										<div class="col-md-6 fec">
											00:25 02-03-2016
										</div>
										<div class="col-md-6 aut">
											escrito por 
												<a href="" >
													Diego
												</a>
										</div>
									</div>
								</div>
								<div class="resNot">
									Empezamos la Actualidad ROM de esta semana, con interesantes novedades en el mundo de la Scene Android, como pueda ser la llegada de la primera versión estable y oficial de una compilación CyanogenMod 13, la actualización de SuperSU o la publicación del código fuente del Kernel para el Samsung Galaxy S7 Edge Exynos. Esto y mucho más, a continuación.
								</div>
								<div class="clavNot text-muted">
									<a href="">
										Android
									</a>
									<a href="">
										smartphone
									</a>
									<a href="">
										rom
									</a>
									<a href="">
										samsung
									</a>
								</div>
							</div>
							<!--Hasta aqui en bucle-->
							<div class="pantNot">
								<div class="tituloNot">
									<h3><a href="#">
										Así imaginan el diseño del iPhone SE justo antes de su presentación
									</a></h3>
								</div>
								<div class="imgNot">
									<img src="./img/imgNot2.jpg" alt="imagen noticia" class="img-responsive">
								</div>
								<div class="datosNot">
									<div class="row">
										<div class="col-md-6 fec">
											00:25 02-03-2016
										</div>
										<div class="col-md-6 aut">
											escrito por 
												<a href="" >
													Diego
												</a>
										</div>
									</div>
								</div>
								<div class="resNot">
									Estamos a escasos minutos de conocer finalmente si el iPhone SE se presenta, o no porque con Apple nunca se sabe, y la empresa de Cupertino lanza la que se supone es su apuesta para el mercado de gama media – y esperemos que esta vez sea con algo más de idea que con el iPhone 5c -. Mientras esperamos y para amenizar el rato, no falta quien nos quiere dar el último intento de adelanto del diseño de los nuevos terminales, recogiendo todos los rumores habidos y por haber sobre los equipos.
								</div>
								<div class="clavNot text-muted">
									<a href="">
										Iphone
									</a>
									<a href="">
										smartphone
									</a>
									<a href="">
										rom
									</a>
								</div>
							</div>
							<div class="pantNot">
								<div class="tituloNot">
									<h3><a href="#">
										Cómo llevar dos SIM y la microSD al mismo tiempo en el Samsung Galaxy S7
									</a></h3>
								</div>
								<div class="imgNot">
									<img src="./img/imgNot3.jpg" alt="imagen noticia" class="img-responsive">
								</div>
								<div class="datosNot">
									<div class="row">
										<div class="col-md-6 fec">
											00:25 02-03-2016
										</div>
										<div class="col-md-6 aut">
											escrito por 
												<a href="" >
													Diego
												</a>
										</div>
									</div>
								</div>
								<div class="resNot">
									Una de las grandes novedades del Samsung Galaxy S7 es la inclusión de nuevo de una ranura de tarjetas microSD para poder aumentar el almacenamiento disponible mediante esta tradicional herramienta. En este caso no se trata de una ranura microSD como tal, sino que esta se integra dentro de la ranura doble SIM, donde podemos elegir llevar dos de estas tarjetas o bien una SIM junto a una tarjeta microSD. A continuación os contamos cómo llevar dos SIM y la microSD al mismo tiempo en el Samsung Galaxy S7.
								</div>
								<div class="clavNot text-muted">
									<a href="">
										Samsung
									</a>
									<a href="">
										smartphone
									</a>
									<a href="">
										rom
									</a>
								</div>
							</div>
							<!--Eliminar hasta aqui-->
						</div>
						<div class="col-md-3" id="idPanelPubli">
							<div id="panel-inf1" class="panel-inf1 panel-fixed">
								<div class="panel1">
									<div class="cab-inf">
										<h2>¿No encuentras lo que buscas?</h2>
									</div>
									<div class="centro-inf">
										<h3>Preg&uacute;ntalo en nuestros foros</h3>
									</div>
									<div class="pie-inf">
										<a href="#">
											<img src="./images/foro.png" alt="Foro MundoMovil">
										</a>
									</div>
								</div>
								<div class="panel2">
									<div class="cab-inf">
										<h2>¿Quieres aprender nuevos trucos?</h2>
									</div>
									<div class="centro-inf">
										<h3>Sigue nuestros tutoriales</h3>
									</div>
									<div class="pie-inf">
										<a href="#">
											<img src="./images/tutoriales.png" alt="Foro MundoMovil">
										</a>
									</div>
								</div>								
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-9">
					<nav class="paginacion">
						<ul class="pagination">
							<li>
								<a href="#" aria-label="Anterior">
									<span aria-hidden="true">&laquo;</span>
								</a>
							</li>
							<li><a href="#">1</a></li>
							<li class="active"><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">4</a></li>
							<li><a href="#">5</a></li>
							<li>
								<a href="#" aria-label="Siguiente">
									<span aria-hidden="true">&raquo;</span>
								</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
