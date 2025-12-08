use core::include_str;
use std::collections::HashMap;

pub fn run() {
    println!("Running Day 06");
    solve();
}

fn solve() {
    let instructions: Vec<Vec<(usize, String)>> =
        include_str!("input/day06-instructions.txt")
            .lines()
            .map(|line| {
                // Normalize whitespace
                let cleaned = line.split_whitespace().collect::<Vec<_>>();

                // Produce (index, value) pairs
                let indexed: Vec<(usize, String)> = cleaned
                    .iter()
                    .enumerate()
                    .map(|(i, v)| (i, v.to_string()))
                    .collect();

                indexed
            })
            .collect();

    let mut results: HashMap<usize, u64> = HashMap::new();

    let _: Vec<_> = include_str!("input/day06.txt")
        .lines()
        .map(|line| {
            let instruction_parts: Vec<&str> = line.split_whitespace().collect();

            for part in 0..instruction_parts.len() {
                let operation = &instructions[0][part].1;
                let v = instruction_parts[part].to_string().parse::<u64>().unwrap();

                results
                    .entry(part)
                    .and_modify(|number| {
                        if *number != 0 {
                            if operation == "*" {
                                *number = v * *number;
                            } else {
                                *number = v + *number;
                            }
                        } else {
                            *number = v;
                        }
                    })
                    .or_insert(v);
            }
        }).collect();

    let total: u64 = results.values().copied().sum();

    println!("Part 1: {}", total);
}
