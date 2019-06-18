		<div class="footer-space"></div>

		<script>
			$(function(){
				$(window).scroll(function(){
					if(this.pageYOffset>100){
						$('#header').addClass('fixed');
					}else{
						$('#header').removeClass('fixed');
					}
				})
			});
		</script>
	</body>
</html>