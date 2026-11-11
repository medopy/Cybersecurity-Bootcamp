import hashlib
import time
import os

def calculate_file_hash(filepath):
    """Calculate the SHA-256 hash of a file efficiently using chunking."""
    sha256_hash = hashlib.sha256()
    try:
        with open(filepath, "rb") as f:
            for byte_block in iter(lambda: f.read(4096), b""):
                sha256_hash.update(byte_block)
        return sha256_hash.hexdigest()
    except FileNotFoundError:
        return None

def start_fim(target_file, check_interval=3):
    print(f"[*] Monitoring sensitive file: {target_file}")
    
    if not os.path.exists(target_file):
        print("[!] Error: Target file does not exist! Please create the dummy file first.")
        return

    baseline_hash = calculate_file_hash(target_file)
    print(f"[+] Baseline Hash established: {baseline_hash}\n")
    print("[*] Modify the file now to test change detection (Press Ctrl+C to stop)...")

    try:
        while True:
            time.sleep(check_interval)
            
            current_hash = calculate_file_hash(target_file)
            
            if current_hash is None:
                print("[!] Alert: Target file has been deleted!")
                break

            if current_hash != baseline_hash:
                print("\n" + "="*50)
                print("[!] [CRITICAL ALERT] Unauthorized modification detected!")
                print(f"[!] Old Baseline Hash: {baseline_hash}")
                print(f"[!] New Current Hash:  {current_hash}")
                print("="*50 + "\n")
                
                baseline_hash = current_hash
            else:
                print(".", end="", flush=True) # Heartbeat indicator showing FIM is running smoothly

    except KeyboardInterrupt:
        print("\n[*] File Integrity Monitor stopped manually.")

if __name__ == "__main__":
    file_to_monitor = "sensitive_data.txt"
    
    if not os.path.exists(file_to_monitor):
        with open(file_to_monitor, "w", encoding="utf-8") as f:
            f.write("This is highly confidential data for FIM testing.")
            
    start_fim(file_to_monitor, check_interval=3)
