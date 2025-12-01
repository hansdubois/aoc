extern crate core;

use std::env;

mod days;


fn main() {
    let day = env::args().nth(1).expect("Usage: aoc <day>");

    match day.as_str() {
        "1" | "01" => days::day01::run(),
        _ => eprintln!("Unknown day: {day}")
    }
}