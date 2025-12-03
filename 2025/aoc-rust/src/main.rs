extern crate core;

use std::env;

mod days;

fn main() {
    let day = env::args().nth(1).expect("Usage: aoc <day>");

    match day.as_str() {
        "1" | "01" => days::day01::run(),
        "2" | "02" => days::day02::run(),
        "3" | "03" => days::day03::run(),
        _ => eprintln!("Unknown day: {day}"),
    }
}
