<?php

require_once '../bootstrap.php';

class RecipeParser_Times_Test extends PHPUnit_Framework_TestCase {

    public function test_format_MM() {
        $this->assertEquals(90, RecipeParser_Times::toMinutes(90),
            "Failed to convert integer (90) to 90 minutes,");
    }

	public function test_format_HH_MM() {
        $this->assertEquals(630, RecipeParser_Times::toMinutes('10:30'),
            "Failed to convert 10:30 to 630 minutes.");
	}

    public function test_HH_hr_MM_min() {
        $this->assertEquals(345, RecipeParser_Times::toMinutes('5 Hr 45 Min'),
            "Failed to convert 5 Hr 45 Min to 345 minutes.");
    }

    public function test_HH_hr() {
        $this->assertEquals(720, RecipeParser_Times::toMinutes('12 Hr'),
            "Failed to convert 12 Hr to 720 minutes.");
    }

    public function test_HH_hours() {
        $this->assertEquals(240, RecipeParser_Times::toMinutes('4 Hours'),
            "Failed to convert 4 Hours to 240 minutes.");
    }

    public function test_HH_hour() {
        $this->assertEquals(60, RecipeParser_Times::toMinutes('1 Hour'),
            "Failed to convert 1 Hour to 60 minutes.");
    }

    public function test_MM_minutes() {
        $this->assertEquals(30, RecipeParser_Times::toMinutes('30 Minutes'),
            "Failed to convert 30 Minutes to 30 minutes.");
    }

    public function test_MM_mins() {
        $this->assertEquals(25, RecipeParser_Times::toMinutes('25 Mins'),
            "Failed to convert '25 Mins' to 25 minutes.");
    }

    public function test_MM_mins_lower() {
        $this->assertEquals(20, RecipeParser_Times::toMinutes('20 mins'),
            "Failed to convert '20 mins' to 20 minutes.");
    }

    public function test_MM_min() {
        $this->assertEquals(1, RecipeParser_Times::toMinutes('1 Min'),
            "Failed to convert 1 Min to 1 minute.");
    }

    public function test_frac_hours() {
        $this->assertEquals(135, RecipeParser_Times::toMinutes('2 1/4 hours'),
            "Failed to convert '2 1/4 hours' to 135 minutes.");
    }

    public function test_dec_hrs() {
        $this->assertEquals(150, RecipeParser_Times::toMinutes('2.5 hr'),
            "Failed to convert '2.5 hr' to 150 minutes.");
    }

    public function test_days() {
        $this->assertEquals(2880, RecipeParser_Times::toMinutes('2 days'),
            "Failed to convert '2 days' to 2880 minutes.");
    }

    public function test_hours_minutes_with_comma() {
        $this->assertEquals(265, RecipeParser_Times::toMinutes('4 hours, 25 minutes'),
            "Failed to convert '4 hours, 25 minutes' to 265 minutes.");
    }

    public function test_hour_minutes_with_comma() {
        $this->assertEquals(70, RecipeParser_Times::toMinutes('1 hour, 10 minutes'),
            "Failed to convert '1 hour, 10 minutes' to 70 minutes.");
    }

    public function test_hr_m_with_no_mins() {
        $this->assertEquals(60, RecipeParser_Times::toMinutes('1hr 00m'),
            "Failed to convert '1hr 00m' to 60 minutes.");
    }

    public function test_hr_m() {
        $this->assertEquals(150, RecipeParser_Times::toMinutes('2hr 30m'),
            "Failed to convert '2h 30m' to 150 minutes.");
    }

    public function test_m() {
        $this->assertEquals(15, RecipeParser_Times::toMinutes('15m'),
            "Failed to convert '15m' to 15 minutes.");
    }

    public function test_dash_to_single_time() {
        $this->assertEquals(75, RecipeParser_Times::toMinutes('1 hr - 1 hr 15 mins'),
            "Failed to convert '1 hr - 1 hr 15 mins' to 75 minutes.");
    }

    public function test_dash_to_single_time_nospace() {
        $this->assertEquals(40, RecipeParser_Times::toMinutes('30-40 mins'),
            "Failed to convert '30-40 mins' to 40 minutes.");
    }

    public function test_mm() {
        $this->assertEquals(45, RecipeParser_Times::toMinutes('45 MM'),
            "Failed to convert 45 MM to 45 minutes..");
    }
}

?>
