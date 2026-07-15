import os
import time
import hashlib
import glob
from pathlib import Path

import requests
from watchdog.events import FileSystemEvent, FileSystemEventHandler
from watchdog.observers import Observer
from dotenv import load_dotenv

# TODO: Add an example .env
load_dotenv()
SYNC_DIR = os.getenv('SYNC_DIR')
API_ADDRESS = os.getenv('API_ADDRESS')
SIGNING_KEY = os.getenv('SIGNING_KEY')

print('┏ Environment Variables')
print(f'┃ Sync dir is {SYNC_DIR}')
print(f'┃ Remote is {API_ADDRESS}')
print('┗ Environment Variables')
print()

# -- Comparing local and remote files -----------------------------------------
def get_file_hash(file_path: str) -> str:
	hash = hashlib.md5()

	with open(file_path, 'rb') as file:
		for chunk in iter(lambda: file.read(65536), b''): # reads file in 64kb chunks
			hash.update(chunk)

	return hash.hexdigest()

def get_local_hashes() -> dict[str, str]:
	output: dict[str, str] = {}
	
	glob_path = str(Path(SYNC_DIR) / '*.txt')
	for file_path in glob.iglob(glob_path):
		file_name = os.path.basename(file_path)
		output[file_name] = get_file_hash(file_path)

	return output

def get_remote_hashes() -> dict[str, str]:
	req = requests.get(API_ADDRESS + '/hashes')
	req.raise_for_status()
	return req.json()

def find_out_of_sync_files() -> list[str]:
	local_hashes = get_local_hashes()
	remote_hashes = get_remote_hashes()
	evil_files: list[str] = []

	for filename in local_hashes:
		if local_hashes.get(filename) != remote_hashes.get(filename):
			evil_files.append(filename)

	return evil_files

# -- Sync files ---------------------------------------------------------------
def upload_file(filename: str) -> None:
	print(f'║ Syncing {filename}...')
	file_path = str(Path(SYNC_DIR) / filename)
	with open(file_path) as file:
		file_content = file.read()

	# TODO: Add request signing
	req = requests.put(API_ADDRESS + '/users/' + filename, data=file_content)
	req.raise_for_status()
	print(f'║ {filename} OK!')

def sync_files() -> None:
	print('┏ Sync Operation')
	print('┃ Diffing files...')
	out_of_sync = find_out_of_sync_files()
	print(f'┃ {len(out_of_sync)} found! Uploading...')
	for filename in out_of_sync:
		upload_file(filename)
	print('┗ Done!')

# -- Watcher setup ------------------------------------------------------------
class SyncingChangeHandler(FileSystemEventHandler):
	def on_modified(self, event: FileSystemEvent):
		if event.is_directory: return
		print(f'{event.src_path} modified! Syncing...')
		sync_files()
		print()

# -- Set it all in motion -----------------------------------------------------
print("Performing initial sync...")
sync_files()
print()

print("Setting up observer...")
event_handler = SyncingChangeHandler()
observer = Observer()
observer.schedule(event_handler, SYNC_DIR)
observer.start()

print("OK, holding.")
try:
	while True:
		time.sleep(1) # keep alive while the observer does its thing
finally:
	observer.stop()
	observer.join()
