use core::include_str;

pub fn run() {
    println!("Running Day 01");
    solve_part1();
    solve_part2();
}
fn solve_part1() {
    let contents = include_str!("input/day01.txt");

    let mut total: i32 = 50;
    let mut counter: i32 = 0;

    contents.split('\n').for_each(|a| {
        let mut iter = a.chars();
        if let Some(first) = iter.next() {
            let rest: String = iter.collect();
            let number = rest.parse::<i32>().unwrap();

            if first == 'R' {
                let remainer = number % 100;

                total += remainer;

                if total > 0 {
                    total -= 100;
                }
            } else if first == 'L' {
                let remainer = number % 100;

                total -= remainer;

                if total < 0 {
                    total += 100;
                }
            }

            if total == 0 {
                counter += 1;
            }
        }
    });

    println!("Part 1: {}", counter);
}

fn solve_part2() {
    let mut total: i32 = 50;
    let mut counter: i32 = 0;

    include_str!("input/day01.txt").lines().for_each(|a| {
        let mut iter = a.chars();
        if let Some(first) = iter.next() {
            let rest: String = iter.collect();
            let number = rest.parse::<i32>().unwrap();

            if first == 'R' {
                for _ in 0..number {
                    total += 1;

                    if total == 100 {
                        total = 0;
                    }

                    if total == 0 {
                        counter += 1;
                    }
                }
            } else if first == 'L' {
                for _ in 0..number {
                    total -= 1;

                    if total < 0 {
                        total = 99;
                    }

                    if total == 0 {
                        counter += 1;
                    }
                }
            }
        }
    });

    println!("Total: {}", counter);
}
