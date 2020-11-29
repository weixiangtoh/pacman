<?php 
	require_once("Point.php");
	session_start();
	require_once("utility.php");
	$animation = "";
	if(isset($_POST["find_path"])){
		$animation = "onload='animation()'";
	}
?>

<html>
	<head>
		<title>Pacman Maze</title>
		<link rel="icon" type="image/png" href="mario.png">
		<script type="text/javascript" src="animation.js"></script>
		<!-- bootstrap -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="maze.css"/> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<body <?= $animation?>>
		<div class='container my-3'>
			<form action="maze.php" method="post">
				<div class="form-row ml-1">
					<div class="form-group row">
						<label for="heightbox" class="col-4 col-form-label">Height:</label>
						<div class="col-7">
							<?php generate_height_box();?>
						</div>
					</div>
					
					<div class="form-group row">
						<label for="widthbox" class="col-4 col-form-label">Width:</label>
						<div class="col-7">
							<?php generate_width_box();?>
						</div>
					</div>
				</div>
				
				<div class="form-row ml-1">
				<?php 
						# Repopulate include obstacles checkbox 
						$has_obstacles = "";
						if(isset($_POST["has_obstacles"])){
							if ($_POST["has_obstacles"]){
								$has_obstacles = "checked";
							} 
						}
					?> 
					<div class="form-group">
						<div class="form-check">
							<input type="checkbox" name="has_obstacles" id="has_obstacles" class="form-check-input" <?= $has_obstacles?>/> 
							<label class="form-check-label" for="has_obstacles">
								Include Obstacles
							</label>
						</div>
					</div>

				</div>
				<input type="submit" name="generate_maze" value="Generate Maze" class='btn btn-primary'/>	
				<br/><br/>
				<?php
					$path = [];
					if (isset($_POST["find_path"])){ # When Find Path button is clicked
						$maze = $_SESSION["maze"];
						$start_end = get_start_end_points($maze);
						if($_SESSION["has_obstacles"] === FALSE){
							$path = find_path_no_obstacle($maze, $start_end[0], $start_end[1]);
						}
						else{
							$path = find_path($maze, $start_end[0], $start_end[1]);
						}					
					}
					else{ # When the page loads for the first time OR 
						# when Generate Maze button is clicked
						$has_obstacles = FALSE;
						if(isset($_POST["has_obstacles"])){
							$has_obstacles = $_POST["has_obstacles"];
						}
						$maze = generate_maze($has_obstacles);
						$_SESSION["maze"] = $maze;
						$_SESSION["has_obstacles"] = $has_obstacles;
					}
					echo "<table class='table-responsive'>
							<tr>
								<td>";
					display_maze($maze);
					echo "		</td>
							</tr>
					</table>
					<br/>";
					// echo "		</td>
					// 			<td style='padding:50px'>
					// 				<div id='motion'>
					// 				</div>
					// 				<div id='result'>
					// 				</div>
					// 			</td>
					// 		</tr>
					// </table>
					// <br/>";
					echo "<input type='hidden' id='hiddenpath' value='" . implode($path) . "'/>";
					?>
					<div class="card mb-2" style="width:260px;">
						<div class="card-body">
							<p id='motion'></p>
							<p id='result'></p>
						</div>
					</div>
					
				<input type="submit" name="find_path" value="Find Path" class='btn btn-primary mt-4'/>
			</form>	
		</div>
	
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
	</body>
</html>