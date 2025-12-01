use core::include_str;
use std::collections::HashMap;

pub fn run() {
    println!("Running Day 01");
    solve_part1();
    solve_part2();
}
fn solve_part1() {
    let contents = include_str!("input/day01.txt");

    let mut total:i32 = 0;


    let _a = contents.split('\n').for_each(|a|
        {
            let mut iter = a.chars();
            if let Some(first) = iter.next() {
                let rest: String = iter.collect();

                if first == '+' {
                    total += rest.parse::<i32>().unwrap();
                } else if first == '-' {
                    total -= rest.parse::<i32>().unwrap();
                }
            }
        }
    );

    println!("Part 1: {}", total.to_string());

}
fn solve_part2() {
    let contents = include_str!("input/day01.txt");

    let mut total:i32 = 0;
    let mut seen: HashMap<i32, bool> = HashMap::new();
    seen.insert(0, true);

    'outer: loop {
        for a in contents.split('\n') {
            let mut iter = a.chars();
            if let Some(first) = iter.next() {
                let rest: String = iter.collect();

                if first == '+' {
                    total += rest.parse::<i32>().unwrap();
                } else if first == '-' {
                    total -= rest.parse::<i32>().unwrap();
                }
            }

            if seen.contains_key(&total) {
                break 'outer;
            } else {
                seen.insert(total, true);
            }
        }
    }

    println!("Part 2: {}", total.to_string());
}