use core::include_str;
use std::str::Chars;

struct ValuePosition {
    value: u64,
    position: usize,
}

pub fn run() {
    println!("Running Day 03");
    solve();
    solve2();
}

fn solve() {
    let sum = include_str!("input/day03.txt")
        .lines()
        .map(|bank| {
            let tenth = find_highest_with_start_and_remaining(bank.chars(), 0, 1);
            let single = find_highest_with_start_and_remaining(bank.chars(), tenth.position + 1, 0);

            (tenth.value * 10) + single.value
        })
        .collect::<Vec<u64>>()
        .iter()
        .sum::<u64>();

    println!("Part 1: {}", sum);
}

fn solve2() {
    let total: u64 = include_str!("input/day03.txt")
        .lines()
        .map(|bank| {
            let mut stack: Vec<u64> = Vec::new();
            let mut last_position = 0;

            for i in 0..12 {
                let value_position =
                    find_highest_with_start_and_remaining(bank.chars(), last_position, 11 - i);
                last_position = value_position.position + 1;

                stack.push(value_position.value);
            }

            stack
                .iter()
                .map(u64::to_string)
                .collect::<String>()
                .parse::<u64>()
                .unwrap()
        })
        .sum();

    println!("Part 2: {}", total);
}

fn find_highest_with_start_and_remaining(
    chars: Chars,
    start: usize,
    remaining: usize,
) -> ValuePosition {
    let mut highest: u64 = 0;
    let mut position = start;

    for i in start..chars.clone().count() - remaining {
        let value = chars.clone().nth(i).unwrap().to_digit(10).unwrap() as u64;

        if value > highest {
            highest = value;
            position = i;
        }

        if highest == 9 {
            break;
        }
    }

    ValuePosition {
        value: highest,
        position,
    }
}
