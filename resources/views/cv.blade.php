<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>CV Alexis Vargas</title>
	
	<link rel="stylesheet" href="css/cv.css" />
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
	<div class="container">
		<header>
			<h1 id="name" class="w3-center">Alexis Vargas</h1>
			<div id="designation" class="w3-center">		
				<span class="title" ><i>Full Stack Developer (PHP, Ruby, VueJS)</i></span><br>
				<span class="organization"><i>Shore Canada</i></span>		
			</div>

			<div class="contact w3-center">
				<div class="email">lexisvar@gmail.com</div>
			</div>		
		</header>	
		<div class="content" role=main>	
			<section id="objective">
				<h2 class="title">About</h2>
				<hr class="hr-title">
				<div class="description">
					<p>Hello! I am Anthony Barnett. Web Developer, Graphic Designer and Photographer.
	        		Creative CV is a HTML resume template for professionals. Built with Bootstrap 4, 
        			Now UI Kit and FontAwesome, this modern and responsive design template is perfect to showcase your portfolio, skills and experience. Learn More
					</p>			
				</div> <!-- end description -->     
			</section>    
			<section id="experience">
				<h2 class="title">Experience</h2>
				<hr>
				<div class="description">	
					<ul>
					@foreach ($experiences as $experience)
        				<!-- Project 1 -->
						<li>
							<section class="project">
								<h3 class="title">{{$experience->occupation}}</h3>
								<div class="meta">
									<span class="field">{{$experience->company}}</span> 
									<span class="date">{{$experience->start_date}} - {{$experience->finish_date}}</span>
								</div>
								<p class="description">{{$experience->description}} </p>
							</section> <!-- end project description -->							
						</li>
        			@endforeach
					</ul>			
      			</div>
			</section>			
			<section id="education">
				<h2 class="title">Education</h2>
				<hr>
				<div class="description">	
					<ul>
					@foreach ($educations as $education)
        				<!-- Project 1 -->
						<li>
						<section class="project">
        			    	<h3 class="title">{{$education->study_name}}</h3>
        			    	<div class="meta">
        			      		<span>{{$education->college}}</span> <span class="date">{{$education->start_date}} - {{$education->finish_date}}</span>
        			    	</div>
        			    	<p class="description">{{$education->description}} </p>
        			  	</section> <!-- end project description -->
						</li>
        			@endforeach
					</ul>			
      			</div>
			</section>
    		<section id="skills">
				<h2 class="title">Skills</h2>
				<hr>
				<div class="description">				
        			@foreach ($skills as $skill)          
        	  		<section class="skill">
        	    		<div class="w3-light-grey w3-round-large">
        	      			<div class="w3-grey w3-round-large w3-center" style="height:24px;width:<?= $skill->percent ?>%;"> {{$skill->percent}}% 
							</div>
        	    		</div><br>
        			</section> 
        			@endforeach
      			</div>
			</section>
	
			<!-- Training/ seminars -->
			<!-- Scholistics Achievements -->
			<!-- Technical Skills -->
			<!-- Extra-Curricular Activities -->
			<!-- Personal Informations -->
			<!-- References -->
			<!-- Declarations -->
			<!-- signatory -->
		</div> <!-- end main content -->		
	</div> <!-- end container -->	
</body>
</html>