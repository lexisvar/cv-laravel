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
		<header class="w3-row">
			<div class="w3-col s6">
				<h1 id="name">Alexis Vargas</h1>
				<div id="designation">		
					<span class="title" ><i>Full Stack Developer (PHP, Ruby, VueJS)</i></span><br>
				</div>
			</div>
			<div class="w3-col s6 w3-right-align">
				<div id="designation">		
					<span class="github"><a href="https://github.com/lexisvar/">github</a></span><br>
					<span class="linkedin"><a href="https://www.linkedin.com/in/lexisvar/">LinkedIn</a></span><br>
					<span class="page"><a href="http://lexisvar.me/">lexisvar.me</a></span>
				</div>
				<div class="contact">
					<div class="email">lexisvar@gmail.com</div>
				</div>
			</div>		
		</header>	
		<div class="content" role=main>	
			<section id="objective">
				<h2 class="title">About</h2>
				<hr class="hr-title">
				<div class="description">
					<p>
            I am a highly skilled and experienced software developer with a passion for scripting languages like 
            Python, Ruby and PHP, as well as Linux and Unix systems. I have a diverse range of experience in 
            different industries and have worked on a variety of projects using a wide range of technologies. 
            I have a deep understanding of backend development and have experience building robust and scalable APIs.
             In addition to my technical skills, I have strong leadership and team management abilities and am able 
             to deliver projects on time and under budget. I am a FIDE Master in chess and enjoy practicing the game 
             and soccer. My passion for programming and chess sport makes me to continuously improve my problem-solving and strategic thinking abilities.
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
									<span class="date">{{ date('M Y', strtotime($experience->start_date)) }} - {{ $experience->finish_date ? date('M Y', strtotime($experience->finish_date)) : 'CURRENT' }}</span>
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
					<ol>
					@foreach ($skills as $skill)  
					<li>
						<section class="skill">
							<h5 class="title">{{$skill->name}}</h3>
							<div class="w3-light-grey w3-round-large w3-small">
								<div class="w3-grey w3-round-large w3-center" style="width:<?= $skill->percent ?>%;"> {{$skill->percent}}% 
								</div>
							</div><br>
						</section>
					</li>        
 
        			@endforeach

					</ol>				
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