use core::include_str;

pub fn run() {
    println!("Running Day 04");
    solve();
}
fn solve() {
    let lines: Vec<&str> = include_str!("input/day04.txt").lines().collect();

    let height = lines.len();
    let width = lines[0].len();

    let mut grid = Grid::new(width, height, ".".to_string());

    for (y, line) in lines.iter().enumerate() {
        for (x, ch) in line.chars().enumerate() {
            grid.set(x, y, ch.to_string());
        }
    }

    let mut counter = 0;
    let mut total_removed = 0;

    loop {
        counter += 1;
        let remove_count = remove_rolls(&mut grid);
        total_removed += remove_count;

        println!("At count: {} removed {} rolls", counter, remove_count);
        if remove_count == 0 {
            break;
        }
    }

    println!("Total removed: {}", total_removed);
}

fn remove_rolls(grid: &mut Grid<String>) -> i32 {
    let mut accessible_count = 0;
    let mut to_remove: Vec<(usize, usize)> = Vec::new();

    for (x, y, val) in grid.iter() {
        if val == "@" {
            let neighbors = grid
                .neighbors8(x, y)
                .into_iter()
                .filter(|(_, _, value)| *value == "@")
                .count();

            if neighbors < 4 {
                accessible_count += 1;

                to_remove.push((x, y));
            }
        }
    }

    to_remove.iter().for_each(|(x, y)| {
        grid.set(*x, *y, ".".to_string());
    });

    accessible_count
}

#[derive(Debug, Clone)]
pub struct Grid<T> {
    width: usize,
    height: usize,
    cells: Vec<T>,
}

impl<T: Clone> Grid<T> {
    pub fn new(width: usize, height: usize, default: T) -> Self {
        Self {
            width,
            height,
            cells: vec![default; width * height],
        }
    }

    fn index(&self, x: usize, y: usize) -> Option<usize> {
        if x < self.width && y < self.height {
            Some(y * self.width + x)
        } else {
            None
        }
    }

    pub fn get(&self, x: usize, y: usize) -> Option<&T> {
        self.index(x, y).map(|i| &self.cells[i])
    }

    pub fn set(&mut self, x: usize, y: usize, value: T) -> bool {
        if let Some(i) = self.index(x, y) {
            self.cells[i] = value;
            true
        } else {
            false
        }
    }

    pub fn iter(&self) -> impl Iterator<Item = (usize, usize, &T)> {
        self.cells.iter().enumerate().map(move |(i, v)| {
            let x = i % self.width;
            let y = i / self.width;
            (x, y, v)
        })
    }

    pub fn neighbors8(&self, x: usize, y: usize) -> Vec<(usize, usize, &T)> {
        let mut result = Vec::with_capacity(8);

        let dirs = [
            (-1isize, -1isize),
            (0, -1),
            (1, -1),
            (-1, 0),
            (1, 0),
            (-1, 1),
            (0, 1),
            (1, 1),
        ];

        for (dx, dy) in dirs {
            let nx = x as isize + dx;
            let ny = y as isize + dy;

            // Check bounds
            if nx >= 0 && ny >= 0 {
                let (nx, ny) = (nx as usize, ny as usize);
                if let Some(value) = self.get(nx, ny) {
                    result.push((nx, ny, value));
                }
            }
        }

        result
    }
}
