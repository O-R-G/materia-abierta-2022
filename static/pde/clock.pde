/* @pjs transparent=true; */
/* @pjs crisp=true; */

// for Materia Abierta 2020
// O-R-G 

float powerCounter = 0;
int resonatorCounter = 0;
int displayCounter = 0;
int reverseSwitch = 1;
int vertex_points_min = 10;     // minimum spiral complexity 30 [60] 120 240
                                // modulated over time by hh:mm:ss
                                // 00:00:00 + 0.0 
                                // 23:59:59 + 240.0 

void setup() {
  // sizer passed from views/clock.php via js
  size(sizer,sizer);   // [200, 200] adjustable
  smooth();
  background(0,0);
  stroke(255);
  strokeWeight(2);
  noFill();
  offset = width/2;
  scale = 0.75;
  line_scale = offset * scale;
  vertex_scale = scale * offset/100;
  vertex_offset = offset/(width/2);
}

void draw() {
  background(0,0);

  displayCounter++;  
  if ( displayCounter % 14 == 0 )
     resonatorCounter++;
  powerCounter = (((displayCounter % 300 ) / 3) * reverseSwitch) + 2.0;

  float s = map(displayCounter % 60, 0, 60, 0, TWO_PI) - HALF_PI;
  float m = map((resonatorCounter % 60) + 30, 0, 60, 0, TWO_PI) - HALF_PI;
  float h = map(hour() % 12, 0, 12, 0, TWO_PI) - HALF_PI;
  float now = (hour() * 60 * 60) + (minute() * 60) + second();
  float now_adjust = map(now, 0, 86399, 0, 240);

  line(offset, offset, -cos(s) * line_scale + offset, sin(s) * line_scale + offset);
  translate(offset, offset);  
  vertex_points = vertex_points_min + now_adjust;
  vertex_scaled = 100/vertex_points * vertex_scale;
  beginShape();
  for(int i = 0; i < vertex_points; i++) 
      curveVertex((i*vertex_scaled)*sin(i/powerCounter),(i*vertex_scaled)*cos(i/powerCounter));
  endShape(); 
}
