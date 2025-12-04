extern crate core;

use std::env;
use std::time::Instant;

mod days;

fn main() {
    let day = env::args().nth(1).expect("Usage: aoc <day>");

    let start = Instant::now();

    match day.as_str() {
        "1" | "01" => days::day01::run(),
        "2" | "02" => days::day02::run(),
        "3" | "03" => days::day03::run(),
        "4" | "04" => days::day04::run(),
        _ => eprintln!("Unknown day: {day}"),
    }

    let elapsed = start.elapsed();
    println!();
    println!("Total elapsed: {:.3?}", elapsed);
}
