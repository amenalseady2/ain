<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
* get_tweets.php
* Collect tweets from the Twitter streaming API
* This must be run as a continuous background process
* Latest copy of this code: http://140dev.com/free-twitter-api-source-code-library/
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.30
*/

$fp = fopen('process_id_get_tweets.txt','w');
fwrite($fp,getmypid());
fclose($fp);

require_once('140dev_config.php');

require_once('../libraries/phirehose/Phirehose.php');
require_once('../libraries/phirehose/OauthPhirehose.php');
class Consumer extends OauthPhirehose
{
  // A database connection is established at launch and kept open permanently
  public $oDB;
  public function db_connect() {
    require_once('db_lib.php');
    $this->oDB = new db;
  }
	
  // This function is called automatically by the Phirehose class
  // when a new tweet is received with the JSON data in $status
  public function enqueueStatus($status) {
    $tweet_object = json_decode($status);
		
		// Ignore tweets without a properly formed tweet id value
    if (!(isset($tweet_object->id_str))) { return;}
		
    $tweet_id = $tweet_object->id_str;

    // If there's a ", ', :, or ; in object elements, serialize() gets corrupted 
    // You should also use base64_encode() before saving this
    $raw_tweet = base64_encode(serialize($tweet_object));
		
    $field_values = 'raw_tweet = "' . $raw_tweet . '", ' .
      'tweet_id = ' . $tweet_id;
    $this->oDB->insert('json_cache',$field_values);
    
    /*$fp = fopen('my_tweets.txt', 'a+');
    fwrite($fp, $field_values . "\n");
    fclose($fp); */
  }
}

// Open a persistent connection to the Twitter streaming API
$stream = new Consumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);

// Establish a MySQL database connection
$stream->db_connect();

// The keywords for tweet collection are entered here as an array
// More keywords can be added as array elements
// For example: array('recipe','food','cook','restaurant','great meal')
//$stream->setTrack(array('recipe'));
 
 $stream->setFollow(array(
 392069233,
47574889,
72049376,
101192750,
14848686,
14556875,
105070447,
254470156,
205851197,
90690412,
2834324514,
124544966,
465536178,
421750989,
343244344,
1337219042,
340983871,
31415327,
2545572229,
911191633,
486017223,
198823085,
91839477,
959919907,
27836509,
1961088564,
99252919,
703796234,
536199297,
446905360,
//23216791, ng4a
301673373,
78626346,
191270274,
275481498,
2355651464,
275481498,
762158190,
304982427,
258677847,
267291373,
242750665,
836503352,
130430200,
285735127,
576241232,
1384110896,
142264595,
292784297,
927107700,
111967510,
108960066,
275558381,
225344708,
124184307,
376155465,
400280691,
115361975,
184872691,
300140770,
296931186,
311439391,
318451105,
336025782,
239934539,
292228323,
168972684,
105194175,
153436029,
344699554,
546751866,
217275219,
1033035534,
116393932,
596303742,
360364284,
1846262448,
727935997,
80233034,
195651241,
2999511210,
418856515,
1865166570,
//269272754,   //النادي error
712731140,   //النادي
1576111052,
321980982,
1427533741,
341397738,
510020549,
239529428,
780640602,
348013440, //نادي الشباب السعودي
197600746,
382300763,
28631535,
784846530,
47009541,
83620994,
125317437,
32528708,
107435780,
80824820,
333268176,
275791729,
714901142,
70354790,
460304655,
288120714,
398869642,
275005376,
275558381,
490135740,
52366199,
908749393,
1062337568,
332566057,
354250902,
426590614,
1246659666,
193883749,
258820037,
69861960,
67490839,
346582615,
87241441,
373257377,
373299032,
2613392268,
264806429,
553572500,
311969732,
112441342,
225758260,
434299510,
1289128951,
2327034246,
1670790157,
2381769289,
42967165,
2556809431,
521511592,
221120350,
1072915220,
521511592,
94974346,
84620593,
311489967,
406527960,
1264208004,
575661707,
19482174,
2799385956,
239501900,
262125327,
20120818,
44335525,
118629205,
398593054,
113091297,
269208186,
245956466,
21854990,
313159915,
2278843506,
296678194,
543446813,
166522734,
234504612,
183911616,
60327493,
400823888,
325338195, //ابو ظبي
82615868,
255391751,
16437955,
30627754,
861198205,
251826832,
408345314,
266015915,
162172633,
256937266,
931730593,
399950726,
142045890,
131118286,
543205320,
278414593,
50548818,
37599685,
193194851,
385843634,
190985389,
245234010,
//436589187,  //ـلفزيون دولة الكويت
50412992,
3139357051,
255455212,
321215200,
49263332,
266710172,
256919866,
2922862390,
2514952831,
1019840624,
327722701,
252501411,
2291448871,
2342542776,
183926590,
357826998,
145151358,
395853231,
247382519,
443632125,
235889942,
235892571,
252405087,
226396406,
239749021,
258307818,
77072285,
1170101,
290978136,
266093898,
260664856,
262299947,
214540048,
231687318,
76679449,
263058928,
272518817,
456792557,
936792788,
257502383,
1112435550,
248284490,
870315216,
385160961,
293245266,
556727039,
97875681,
143531181,
311932430,
362327478,
83839801,
230468491,
429791797,
860974532,
569097948,
124671425,
485509607,
838165620,
162140495,
1907807612,
631928147,
430530678,
258755880,
82977881,
144105935,
295123952,
78279812,
408795883,
370232918,
260162879,
169196848,
2361421632,
973861340,
1057921008,
266468024,
604360798,
341375478,
325268167,
284470387,
116424578,
497788324,  //anadool
228469418,
303255263,
2547827340,
105570727,
1598482027,
449254379,
2595903860,
825599983,
524256057,
260170670,
605491361,
372006123,
535896480,
115486067,
599345764,
1867475088,
521721314,
319812389,
334276836,
1735534946,
2449746186,
434897540,
528269851,
1725787992,
290579007,
1057838352,
329583995,
2363967920,
918828560,
337308978,
1073307144,
116040486,
1386550285,
963107352,
421598074,
2210854020,
1454921030,
230373679,
224623441,
767184145,
39592755,
360207289, //افاق نيوز
278519480,
607238670,
478180163,
//1083854941,   //العراق تايمز - الموقع ضارب
1854098244,
803263098,
1147940401, //الفرات نيوز
35511948,
533454902,
1976180148,
410257747,  //صيدا اون لاين
1390688994,
951821275,
522154603,  //qatar tv
385282961, //وكالة الانبار العمانية
1743239899,
245286681,
384242471,
751321436,
207152105,
2197308494,
79942437,
107359225,
130125697,
2256678577,
166513119,
2878635204,
256116885,
1245690841,
181906382,
956256691,
54509416,
134972365,
501815999,
214185838,
923569404,
2327617496,
249610058,
419677047,
225173432,
1362191299,
38162949,
122969292,
471336896,
334865866,
88641180,
228027756,
282976624,
230669478,
1459705808,
1119959444,
191353654,
1949744953,
284951330,
99856628,
143554229,
327465680,
717000649,
2861092961,//وقت نيوز
2388883087,
437074266,
2172040267,
2472192810,
1026662552,
2817502748,
598753604,
439383223,
990916915,
41360559,
732919374,
439094703,
//553825880,  //البلد العمانية
951230040,
2882944579,    //شرطة عمان
2172615885,
221120350,
984983318,
296134526, //حزب النهضة التونسي
1314131971,//تونس الخضراء
266085354, //المصدر
109556877,
57246038,
2831469775,
455163938,
1640482824,
1230485893,
1286369936,
1632232580,
148801404,
109556877,
140068649,
479934667,
479934667,
141477247,
2230987844,
60370151,
284951330,
224974174,
1432632968,
1432632968,
53059376,
243368411,
78557721, //quds press
248633508, //البغدادية
250745903, // iraq media net
2441679084, //الغربية نيوز
549912548,
103591569,
242706019,
215211299,
927351564,
254074122,
1339344901,
2356399158,
363246406,
329721744,
207997415,
993919992,
101282683,
1708140607,
400062759,
449749253,
420697733,
734833244,
2231394529,
269224482,
377939666,
396075429,
269769714,
493188972,
94347619,
1068408252,
328553539,
177941018,
574367939,
241506665,
274422326,
426138511,
1567592640,
384377810,
317030516,
945310303,
534220965,
515283011,
320595121,
2758878038,
196442530,
548384761,
2494728150,

77745816,
2280211153,
582078247,
3095027801,
2598040244,
275962899,
59126044,
1286369936,
2817502748,
243529205,
1533631423,  //الزاوية
275483405,
543627768,
2941729382,
469520763,
259157798,
153338490,
592555646,
336756343,
794407530,
567373425,
731547816,
2231430008,
421046416,
86908083,
228512942,
372235267,
94072313,
278114952,
134516221,
1127452584,
46292156,
1337219042,
346439390, //المشهد نت

461337998,

46120356,
570675325,
30903241,
111860775,
476628833,
493437919,
92343706,
251731458,
857924528,
479391173,
269995958,
67300789,
276668823,
264119515,
251069109,
225305750,
352505515,

66591836, //العربية cnn

23573083,
256930894,
1130891570,
1084001724,
2880906064,
452008170,
599219943,
302624914, //tawasol
1158897240,
637070498, //fajr news
297629912 //sharayuob and adeyar
 ));
   
    /*$stream->setFollow(array(
    //1234, 5678, 901234573   //The user IDs of the twitter accounts to follow. All of
//   521548868, //ath922_
   67490839,
392069233,
47574889,
72049376,
101192750,
14848686,
14556875,
105070447,
254470156,
205851197,   //ديف تيم
90690412,
2834324514,
124544966,
465536178,
421750989,
343244344,
1337219042,
2381769289, //مركز بيان للاعلام
340983871,
31415327,
2545572229,
78557721,   //quds press
911191633,
486017223,
198823085,
91839477,
959919907,
27836509,
1961088564,
99252919,
703796234,
536199297,
446905360,
23216791,
301673373,
78626346,
191270274,
275481498,
2355651464,
275481498,
762158190,
304982427,
258677847,
267291373,
242750665,
836503352,
130430200,
285735127,
576241232,
1384110896,
142264595,
292784297,
927107700,
111967510,
108960066,
275558381,
225344708,
124184307,
376155465,
400280691,
115361975,
184872691,
300140770,
296931186,
311439391,
318451105,
336025782,
239934539,
292228323,
168972684,
105194175,
153436029,
344699554,
546751866,
217275219,
1033035534,
116393932,
596303742,
360364284,
1846262448,
727935997,
80233034,
195651241,
2999511210,
418856515,
1865166570,
269272754,
1576111052,
321980982,
1427533741,
341397738,
510020549,
239529428,
780640602,
197600746,
382300763,
28631535,
84846530,
221120350,
83620994,
125317437,
32528708,
107435780,
80824820,
275791729,
714901142,
70354790,
460304655,
288120714,
398869642,
275005376,
52366199,
490135740,
52366199,
908749393,
1062337568,
332566057,
354250902,
426590614,
1246659666,
193883749,
69861960,
67490839,
346582615,
325338195, //ابو ظبي
87241441,
373257377,
373299032,
2613392268,
258820037,   //قبق 
264806429,
333268176,  //rotana 
47009541,  //نقطة العلمية 
275558381,  //طارق الحبيب 
553572500,
311969732,
103591569,//رصد
549912548,//الدستور
242706019, //الفجر
215211299,//اخبارك
927351564,//البوابة
254074122,//كلمتي
1339344901,//مصر العربية
2356399158, //ساسه بوست
363246406, //صدى البلد
112441342,
225758260,
434299510,
1289128951,
2327034246,
1670790157,
42967165,
2556809431,
521511592,
221120350,
1072915220,
521511592,
94974346,
84620593,
311489967,
406527960,
1264208004,
575661707,
19482174,
2799385956,
239501900,
262125327,
20120818,
44335525,
118629205,
398593054,
119756265,
113091297,
269208186,
245956466,
21854990,  //arabian business
313159915,
732919374,
2278843506,
543446813,
166522734,
234504612,
183911616,
60327493,
400823888,
82615868,
255391751,
16437955,
151452079,
30627754,
861198205,
251826832,
408345314,
266015915,
162172633,
256937266,
931730593,
399950726,
142045890,
131118286,
543205320,
278414593,
50548818,
37599685,
193194851,
385843634,
190985389,//صندوق الزكاة
245234010,
436589187,
50412992,
//259596543, dwnetwork
3139357051,
255455212,
321215200,
49263332,
52067288,
266710172,
256919866, //حزب الوسط
455986864,
241694977,
2922862390,
2514952831,
1019840624,
166566887,
327722701,
252501411,
2291448871,
2342542776,
183926590,
97232128,
357826998,
145151358,
395853231,
247382519,
443632125,
235889942,
235892571,
252405087,
226396406,
239749021,
258307818,
77072285,
1170101,
290978136,
266093898,
260664856,
262299947,
214540048,
231687318,
76679449,
263058928,
272518817,
456792557,
936792788, //الائتلاف الوطني
257502383,
1112435550,   //مسار الاعلامي
269769714,   // قناة الدنيا 
493188972,// tv shada
248284490,
870315216,
385160961,
293245266,
556727039,
97875681,
143531181,
311932430,
362327478,
//179856308, سودانيز اونلاين
230468491,
83839801, //سودان موشن
429791797,
860974532,
569097948,
124671425,
485509607,
838165620,
162140495,
1907807612,
631928147,
430530678,
258755880,
82977881,
144105935,
295123952,
78279812,
408795883,
370232918,
260162879,
169196848,
2361421632,
973861340,
1057921008,
266468024,
604360798,
296678194,   //qna 
341375478,
325268167,
284470387,
116424578,
228469418,
303255263,
169108336,
2547827340,
105570727,
1598482027,
449254379,
2595903860,
825599983,
524256057,
260170670,
605491361,
372006123,
535896480,
115486067,  //هيئة السوق المالية
599345764, //التحلية
1867475088,
521721314,
319812389,
334276836,
1735534946,
2449746186,
434897540,
528269851,
1725787992,
290579007,
1057838352,
329583995,
2363967920,
918828560,
337308978,
1073307144,
116040486,
1386550285,
963107352,
421598074,
2210854020,
1454921030,
230373679,
224623441,
767184145,
39592755,
360207289,
278519480,
607238670,
478180163,
1083854941,
1854098244,
803263098,
1147940401,
35511948,
533454902,
1976180148,
1390688994,
951821275,
1743239899,
245286681,
384242471,
751321436,
207152105,
2197308494,
79942437,
107359225,
130125697,
2256678577,
166513119,
2878635204,
256116885,
1245690841,
181906382,
429081540,
956256691,
54509416,
134972365,
501815999,
214185838,
923569404,
2327617496,
249610058,
419677047,
225173432,
1362191299,
286722945,
38162949,
122969292,
471336896,
334865866,
88641180,
228027756,
282976624,
230669478,
1459705808,
1119959444,
191353654,
1949744953,
284951330,
99856628,
143554229,
327465680,
717000649,
2861092961,   //وقت نيوز
2388883087,
437074266,
2172040267,
2472192810,
1026662552,
2817502748,
598753604,
439383223,
990916915,
41360559,
439094703,
553825880,
951230040,
2882944579,
2172615885,
221120350,
984983318,
109556877,
57246038,
2831469775,
455163938,
1640482824,
1230485893,
1286369936,
1632232580,
148801404,
109556877,
140068649,
479934667,
6135542,
141477247,
918828560,
2230987844,
60370151,
284951330,
224974174,
497788324, //anadool
1432632968,
53059376,
243368411
,260827637 //anas eid 
/*,15146364 //bayan 
,407579850 //mazen 
/*,521548868 //arh922
        //these users must have given your app permission.
    ));  */

// Start collecting tweets
// Automatically call enqueueStatus($status) with each tweet's JSON data
$stream->consume();

?>