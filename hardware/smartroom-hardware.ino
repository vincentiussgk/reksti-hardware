// kode displaySensor
#include <Wire.h>
//library display
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
//Adafruit_BME280 bme; // I2C
//library sensor
#include <Adafruit_Sensor.h>
//#include <Adafruit_BME280.h>

#include <WiFi.h>
#include <HTTPClient.h>
#include <ESPAsyncWebServer.h>
#include <SPIFFS.h>

#include <EEPROM.h>

#define SCREEN_WIDTH 128 // OLED display width, in pixels
#define SCREEN_HEIGHT 64 // OLED display height, in pixels
// Declaration for an SSD1306 display connected to I2C (SDA, SCL pins)
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, -1);

const char* ssid     = "XXX";
const char* password = "XXX";

const char* serverName = "http://18219024-database.000webhostapp.com/post-esp-data.php";

String apiKeyValue = "tPmAT5Ab3j7F9";

// set pin numbers
const int touchPin = 4;
const int touchExitPin = 13; 
const int ledPin = 16;

// change with your threshold value
const int threshold = 20;
// variable for storing the touch pin value 
int touchValue, touchExitValue;

int totalPeople = 0;

SFEVL53L1X distanceSensor(Wire);

static int ROI_height = 8;
static int ROI_width = 8;
static int center[2] = {0,0};
int Zone = 0;

static int NOBODY = 0;
static int SOMEONE = 1;
static int LEFT = 0;
static int RIGHT = 1;

static int DIST_THRESHOLD_MAX[] = {0, 0};   // treshold of the two zones
static int MIN_DISTANCE[] = {0, 0};

static int PathTrack[] = {0,0,0,0};
static int PathTrackFillingSize = 1; // init this to 1 as we start from state where nobody is any of the zones
static int LeftPreviousStatus = NOBODY;
static int RightPreviousStatus = NOBODY;

void setup(){
  Serial.begin(115200);
  delay(1000); // give me time to bring up serial monitor
  // initialize the LED pin as an output:
  pinMode (ledPin, OUTPUT); 
  Serial.begin(115200);
  if(!display.begin(SSD1306_SWITCHCAPVCC, 0x3C)) {
    Serial.println(F("SSD1306 allocation failed or couldn't find a valid bme280"));
      for(;;);
  }
  delay(2000);
  display.clearDisplay();
  display.setTextColor(WHITE);

  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) { 
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
}

void loop(){

  int CurrentZoneStatus = NOBODY;
  int AllZonesCurrentStatus = 0;
  int AnEventHasOccured = 0;

  distanceSensor.setROI(ROI_height, ROI_width, center[Zone]);  // first value: height of the zone, second value: width of the zone
  delay(delay_between_measurements);
  distanceSensor.setTimingBudgetInMs(time_budget_in_ms);
  distanceSensor.startRanging(); //Write configuration bytes to initiate measurement
  distance = distanceSensor.getDistance(); //Get the result of the measurement from the sensor
  distanceSensor.clearInterrupt();
  distanceSensor.stopRanging();

  if (Distance < DIST_THRESHOLD_MAX[Zone] && Distance > MIN_DISTANCE[Zone]) {
    // Someone is in !
    CurrentZoneStatus = SOMEONE;
  }

  // left zone
  if (zone == LEFT) {

    if (CurrentZoneStatus != LeftPreviousStatus) {
      // event in left zone has occured
      AnEventHasOccured = 1;

      if (CurrentZoneStatus == SOMEONE) {
        AllZonesCurrentStatus += 1;
      }
      // need to check right zone as well ...
      if (RightPreviousStatus == SOMEONE) {
        // event in left zone has occured
        AllZonesCurrentStatus += 2;
      }
      // remember for next time
      LeftPreviousStatus = CurrentZoneStatus;
    }
  }
  // right zone
  else {

    if (CurrentZoneStatus != RightPreviousStatus) {

      // event in left zone has occured
      AnEventHasOccured = 1;
      if (CurrentZoneStatus == SOMEONE) {
        AllZonesCurrentStatus += 2;
      }
      // need to left right zone as well ...
      if (LeftPreviousStatus == SOMEONE) {
        // event in left zone has occured
        AllZonesCurrentStatus += 1;
      }
      // remember for next time
      RightPreviousStatus = CurrentZoneStatus;
    }
  }

  // if an event has occured
  if (AnEventHasOccured) {
    if (PathTrackFillingSize < 4) {
      PathTrackFillingSize ++;
    }

    // if nobody anywhere lets check if an exit or entry has happened
    if ((LeftPreviousStatus == NOBODY) && (RightPreviousStatus == NOBODY)) {

      // check exit or entry only if PathTrackFillingSize is 4 (for example 0 1 3 2) and last event is 0 (nobobdy anywhere)
      if (PathTrackFillingSize == 4) {
        // check exit or entry. no need to check PathTrack[0] == 0 , it is always the case
        Serial.println();
        if ((PathTrack[1] == 1)  && (PathTrack[2] == 3) && (PathTrack[3] == 2)) {
            // this is an entry
            digitalWrite(ledPin, HIGH);
            Serial.println(" - LED on");
            display.clearDisplay();

            totalPeople++;
            
            // display info
            display.setTextSize(1);
            display.setCursor(0,0);
            display.println("Terdeteksi Pengunjung Baru"); //menampilkan hasil deteksi sensor
            float tempAdjustment = random(0,18);
            float tempResult = (float(35) + float(tempAdjustment/10));

            display.print("Suhu Anda: ");
            display.print(tempResult);
            display.println(" C");
            display.print("Total Pengunjung: ");
            display.print(totalPeople); 
            display.display();


            /* To Room Status:
                lamp status on 
                current total ++
            */

           bool isTempImportant = float(tempResult) > 37.50;
           bool isVisitorImporant = totalPeople > 17
           /* 
            To Events:
            1. Temperature
                - is_important: if >37.5
                - type: temperature
            2. visitor
                - is_important: if > 17
                - type: visitor
           */
            String httpRequestData = "api_key=" + apiKeyValue + "&value1=" + String(true)
                           + "&value2=" + String(totalPeople)  
                           + "&value3=" + String(isTempImportant) + "&value4=" + String("temperature") 
                           + "&value5=" + String(isVisitorImporant) + "&value6=" + String("visitor") ;

            int httpResponseCode = http.POST(httpRequestData);
        } else if ((PathTrack[1] == 2)  && (PathTrack[2] == 3) && (PathTrack[3] == 1)) {
          // This an exit
          Serial.println(" - LED on");
          display.clearDisplay();

          if (totalPeople > 0) {
            totalPeople--;
            if (totalPeople == 0) {
              digitalWrite(ledPin, LOW);  
            }
          }
      
          // display info
          display.setTextSize(1);
          display.setCursor(0,0);
          display.println("Selamat tinggal!"); //menampilkan hasil deteksi sensor
          display.print("Total Pengunjung: ");
          display.print(totalPeople); 
          display.display();

          /* To Room Status:
                lamp status off IF totalPeople == 0
                current total --
            */
           bool isLampOn = totalPeople > 0;

           String httpRequestData = "api_key=" + apiKeyValue + "&value1=" + String(isLampOn)
                           + "&value2=" + String(totalPeople);
        }
      }
      for (int i=0; i<4; i++){
        PathTrack[i] = 0;
      }
      PathTrackFillingSize = 1;
    }
    else {
      // update PathTrack
      // example of PathTrack update
      // 0
      // 0 1
      // 0 1 3
      // 0 1 3 1
      // 0 1 3 3
      // 0 1 3 2 ==> if next is 0 : check if exit
      PathTrack[PathTrackFillingSize-1] = AllZonesCurrentStatus;
    }
  }
  delay(500);
  
   
}