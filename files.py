import os

# Set the directory path
dir_path = "/"

# List the files in the directory
files = os.listdir(dir_path)

# Print the file names
for file in files:
    print(file)