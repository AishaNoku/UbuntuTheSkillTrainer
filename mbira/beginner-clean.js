
const courseData = {
    modules: [
        {
            id: "mod-0",
            title: "Module 0: Foundations",
            description: "Cultural background and instrument anatomy",
            lessons: [
                {
                    id: "0.1",
                    title: "What Is the Mbira?",
                    type: "read",
                    content: "<p>The <strong>mbira dzaVadzimu</strong> (literally, 'mbira of the Ancestral Spirits') is a unique musical instrument originating with the Shona people of Zimbabwe. It consists of two primary components: the <strong>soundboard</strong> and the <strong>keys</strong>. The soundboard is a piece of hard, carefully carved wood, which acts as the instrument's body. Extending from the soundboard are 22 to 24 flattened metal keys (or tines) forged from high-carbon steel, arranged in three distinct ranks (two lower rows and one upper row).</p><p>Crucially, the mbira is often played inside a large, dried calabash gourd called a <strong>deze</strong>. The deze acts as a natural resonator, amplifying the mbira's soft tones into the rich, buzzing, layered sound signature to the instrument. Small objects (like bottle caps or shells) are often attached to the soundboard or the deze to create a characteristic rattling, or <em>maseko</em>, that helps carry the sound to the ancestral spirits during ceremonies.</p>",
                    reading: { title: "The Mbira Instrument: Construction and History", url: "https://en.wikipedia.org/wiki/Mbira" },
                    video: { title: "Mbira Anatomy: Keys, Soundboard, and Deze", type: "Demonstration (5:00 min)", url: "https://youtu.be/kmg23ranB9E?si=iwKWYA8wU6hRf8Hn" },
                    frame: '<iframe width="560" height="315" src="https://www.youtube.com/embed/kmg23ranB9E?si=GWZjLW-vt03eG207" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                    quiz: [
                        { q: "What are the three primary materials of a traditional mbira setup?", options: ["Wood, Clay, Copper", "Wood, Metal, Calabash", "Plastic, Bamboo, Bronze"], correct: 1 },
                        { q: "What is the purpose of the 'deze'?", options: ["Protect from rust", "Amplify sound", "Tune the keys"], correct: 1 },
                        { q: "The mbira dzaVadzimu originates with which group?", options: ["Zulu", "Ndebele", "Shona"], correct: 2 }
                    ]
                },
                {
                    id: "0.2",
                    title: "Cultural Context & History",
                    type: "read",
                    content: "<p>The mbira's role in Shona society is profoundly spiritual, far exceeding that of mere entertainment. It is the central instrument in the <strong>Bira</strong> ceremony, an all-night gathering where the community calls upon ancestral spirits (<em>Vadzimu</em>) for guidance, healing, or advice. The repetitive, cyclical music of the mbira is believed to create a trance-like state that allows these spirits to communicate through a host medium.</p><p>Every Shona family and clan identifies itself through a <strong>mutupo</strong> (totem), and the songs associated with these totems tell their history and lineage. This means every song is a living part of the cultural memory. Mbira songs are therefore not written down; they are transmitted orally, passed down directly from one generation of players to the next. The continuity of this music ensures the continuity of the culture itself.</p>",
                    reading: { title: "Mbira in Shona Culture", url: "https://mbira.org/learn/mbira-in-shona-culture/" },
                    frame:'<iframe width="560" height="315" src="https://www.youtube.com/embed/Pbs80p4-nGM?si=Rp4J9ALdMgDElhqJ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                    video: { title: "Traditional Bira Ceremony Clip", type: "Audio (1:30 min)", url: "https://youtu.be/8U3563FPJo8?si=RJ1keXNPHJlktNUM" },
                    quiz: [
                        { q: "What is the main role of mbira in a Bira ceremony?", options: ["Background music", "Connect with spirits", "Counting time"], correct: 1 },
                        { q: "What does 'mutupo' represent?", options: ["Rhythm", "Family/Clan Totem", "Gourd name"], correct: 1 },
                        { q: "Why is the music passed down orally?", options: ["Hard to write", "Secrecy", "Living cultural memory"], correct: 2 }
                    ]
                }
            ]
        },
        {
            id: "mod-1",
            title: "Module 1: First Contact",
            description: "Posture, technique, and your first patterns",
            lessons: [
                {
                    id: "1.1",
                    title: "Holding & Hand Technique",
                    type: "practice",
                    content: "<p>Proper posture is critical for both comfort and clean playing. The mbira is typically held directly in the player's lap or on a cushion. You will use the little finger of the right hand to secure the instrument by sliding it into the hole on the lower right of the soundboard.</p><p>The core of mbira playing is the <strong>thumb-and-index-finger technique</strong>:</p><ul><li><strong>Thumbs:</strong> Both the left and right thumbs rest on top of the keys.</li><li><strong>Index Fingers:</strong> The index finger of each hand is placed on the backside of the mbira, supporting it.</li><li><strong>Plucking:</strong> The right thumb plucks downward on the middle and upper ranks. The left thumb plucks both upward (for the right keys of the lower rank) and downward (for the left keys of the lower rank). This 'dual-action' on the left hand is key to the complex left-hand patterns.</li></ul>",
                    frame: '<iframe width="560" height="315" src="https://www.youtube.com/embed/Hgvey9Xz6vw?si=OkA9KBXhbpa5KFR1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                    video: { title: "Beginner Technique: Hand Position", type: "Close-up (5:30 min)", url: "https://youtu.be/Hgvey9Xz6vw?si=OkA9KBXhbpa5KFR1" },
                    exercises: ["Practice plucking each key cleanly 10 times", "Alternate R-L-R-L slowly for 3 minutes"]
                },
                {
                    id: "1.2",
                    title: "Understanding Rhythm",
                    type: "read",
                    content: "<p>Mbira music is fundamentally <strong>cyclical</strong>. Instead of a song having a beginning, middle, and end, it is built upon a repeating phrase or pattern. The standard cycle is a <strong>12-beat structure</strong>, often referred to as a 'mavembe' pattern. Mastering the 12-beat cycle is the foundation of playing.</p><p>Within this cycle, the left hand typically anchors the pattern with a repeating bass line, while the right hand plays the more melodic, moving parts. The music is built on these interlocking parts.</p>",
                    video: { title: "Clapping the Mavembe", type: "Instructional (1:00 min)", url: "https://youtube.com/shorts/trX5ws_aXTk?si=jxkY8bTS_iSvilmI" },
                    frame: '<iframe width="560" height="315" src="https://www.youtube.com/embed/Hgvey9Xz6vw?si=OkA9KBXhbpa5KFR1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                    exercises: ["Clap and count: 1-2-3-4-5-6-7-8-9-10-11-12", "Tap left hand on beats 1, 4, 7, 10"]
                },
                {
                    id: "1.3",
                    title: "First Pattern: Two Note Motif",
                    type: "practice",
                    content: "<p>Now, we apply the rhythmic timing to the keys! This first pattern is designed solely to build muscle memory between the two hands and establish a clean, consistent rhythm. We will use two specific keys: one from the lower left rank (Key L2) and one from the upper right rank (Key R3).</p><div class='info-box'><p class='info-box-title'>Pattern: (L2 - R3) (L2 - R3)</p><p class='info-box-text'>Repeat this 3 times to fill the 12-beat cycle. Focus on making the notes sound interlocking rather than simultaneous.</p></div>",
                    frame: '<iframe width="560" height="315" src="https://www.youtube.com/embed/bAqP3zzFC_4?si=WhpKTEoSUeSxG6R9" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                    video: { title: "Playing the Two-Note Motif", type: "Close-up (1:30 min)", url: "https://youtu.be/4XPv2kIDaXg?si=hNKl8P9QS9boNBpD" },
                    exercises: ["Play slowly for 5 minutes", "Increase speed without tensing up"]
                }
            ]
        },
        {
            id: "mod-2",
            title: "Module 2: First Songs",
            description: "Learning 'Kariga Mombe' and coordination",
            lessons: [
                {
                    id: "2.1",
                    title: "Kushaura (Leading Part)",
                    type: "practice",
                    content: "<p>The foundation of every mbira song is the main part, or <strong>Kushaura</strong> (to lead or start). We will learn a simplified version of a traditional song, such as 'Kariga Mombe' (The Cattle Enclosure) which is known for its strong, grounding rhythm. The Kushaura is the primary melodic pattern that defines the song. When playing alone, the player establishes this part, and the repetition creates the space for variations and spirit communication.</p><p>The first four notes: <em>Left Low Key 5 - Right Middle Key 2 - Left High Key 4 - Right Upper Key 1</em>.</p>",
                    frame: '<iframe width="560" height="315" src="https://www.youtube.com/embed/bAqP3zzFC_4?si=mPW092RrynIN8q-a" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                    video: { title: "Kariga Mombe Walkthrough", type: "Guided (3:00 min)", url: "https://www.youtube.com/results?search_query=kariga+mombe+kushaura+tutorial" },
                    exercises: ["Focus on the first 4 notes", "Practice full 12-beat cycle with metronome"]
                },
                {
                    id: "2.2",
                    title: "Listening & Recognition",
                    type: "read",
                    content: "<p>As a cyclical instrument, pattern recognition is vital. You must train your ear to hear the recurring motifs: the low-end bass line (often played by the left hand's lower keys) and the more dynamic upper-line melodies (often played by the right hand). Listen for sequences that descend (move from higher-pitched keys to lower-pitched keys) or ascend. Identifying these structures will allow you to quickly pick up new songs and recognize where you are in the 12-beat cycle.</p>",
                    video: { title: "Pattern Recognition", type: "Audio examples", url: "https://www.youtube.com/results?search_query=kariga+mombe+mbira+full+song" },
                    frame: '<iframe width="560" height="315" src="https://www.youtube.com/embed/Vdwgo4Ql1lw?si=32ms8En5Qn5Hne3V" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                    quiz: [
                        { q: "Which hand plays the low-pitched rhythm?", options: ["Right", "Left", "Both"], correct: 1 },
                        { q: "Why is pattern recognition important?", options: ["Tuning", "Identifying cycle position", "Volume"], correct: 1 }
                    ]
                },
                {
                    id: "2.3",
                    title: "Hand Coordination",
                    type: "practice",
                    content: "<p>The signature beauty of mbira music comes from the way the left hand and the right hand <strong>interlock</strong>. They are not playing the same rhythm simultaneously; they are weaving together separate, complementary rhythms. This creates the complex, polyrhythmic texture that makes it sound like two musicians are playing. Your task is to transition from thinking of your hands as separate entities to thinking of them as a coordinated rhythmic machine. Focus on developing the 'conversational' feel between your thumbs.</p>",
                    video: { title: "L-R-L-R Coordination Drill", type: "Technique (1:30 min)", url: "https://www.youtube.com/results?search_query=mbira+left+right+hand+coordination" },
                    exercises: ["Practice pattern: L-R-L-R-R-L", "Ensure even timing between plucks"]
                }
            ]
        },
        {
            id: "mod-3",
            title: "Module 3: Song Study",
            description: "Nhemamusasa, variations, and improvisation",
            lessons: [
                {
                    id: "3.1",
                    title: "Nhemamusasa (Kutsinhira)",
                    type: "practice",
                    frame: '<iframe width="560" height="315" src="https://www.youtube.com/embed/L0UW_dPy_Gg?si=WsiErv7ObkHf8btN" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                    content: "<p>'Nhemamusasa' (Temporary Shelter) is one of the oldest and most important songs in the Shona tradition, deeply connected to ceremony and history. It is a vital song for any beginner. While the Kushaura is the lead melody, the <strong>Kutsinhira</strong> (to accompany or follow) is the harmonizing response part. When two mbiras play together, one plays Kushaura and the other plays Kutsinhira, creating a complete, layered sound. Playing the Kutsinhira is often focused on the left hand, providing a steady, rich harmonic and rhythmic base that supports the Kushaura.</p>",
                    video: { title: "Nhemamusasa Kutsinhira", type: "Detailed (4:00 min)", url: "https://youtu.be/hXctgISyWtE?si=ZmrKsMuP4CL2QPj6" },
                    exercises: ["Practice left hand bass pattern only", "Add upper melody once rhythm is stable"]
                },
                {
                    id: "3.2",
                    title: "Variations & Improvisation",
                    type: "read",
                    frame: '<iframe width="560" height="315" src="https://www.youtube.com/embed/BQX9Jf_3haA?si=iN3Movfej9WtwFhY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                    content: "<p>In traditional mbira playing, a song is never played the same way twice. After the basic pattern is established, players are expected to introduce small, spontaneous rhythmic and melodic changes known as <strong>Variations</strong> (<em>kugona</em> - to be able). This is how the song evolves and how the player expresses their creativity. A variation might be as simple as adding one extra pluck to a beat, slightly changing the note order, or momentarily focusing on a different set of keys. The key is to transform the song without losing the main structure or rhythm so the listener can still recognize the root song.</p>",
                    video: { title: "Introducing Rhythmic Variations", type: "Demo (2:00 min)", url: "https://www.youtube.com/results?search_query=mbira+variations+improvisation" },
                    exercises: ["Add one small variation per cycle", "Experiment with tempo changes"]
                },
                {
                    id: "3.3",
                    title: "Recognition Challenge",
                    type: "quiz",
                    content: "<p>The final skill in this beginner course is advanced ear training. You must be able to recognize the different components of the music when listening. Listening to recordings and identifying whether a player is performing the Kushaura or the Kutsinhira strengthens both your pattern memory and your understanding of how the parts interlock. You should also be able to spot the moment a player introduces a Variation—often signaled by a sudden, rhythmic break from the established pattern. This skill prepares you to play effectively with others.</p>",
                    frame: '<iframe width="560" height="315" src="https://www.youtube.com/embed/e6Lw-PM2fdY?si=AbwbilNm-XJnWmox" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                    video: { title: "Recognition Challenge", type: "Audio clips", url: "https://www.youtube.com/results?search_query=nhemamusasa+kushaura+kutsinhira" },
                    quiz: [
                        { q: "The Kushaura is typically:", options: ["The accompanying bass part", "The leading melodic part", "The percussion"], correct: 1 },
                        { q: "What signals a variation?", options: ["Volume change", "Rhythmic break from pattern", "Stopping music"], correct: 1 }
                    ]
                }
            ]
        },
        {
            id: "mod-4",
            title: "Module 4: Intermediate - Karigamombe",
            description: "Multi-phrase complexity, timing control, and endurance",
            lessons: [
                {
                    id: "4.1",
                    title: "Introduction to Karigamombe",
                    type: "practice",
                    content: "<p><strong>Karigamombe</strong> is a classic Shona mbira piece known for its energetic, cyclic rhythm and emotionally uplifting flow. The name means 'the one who fells the ox' and carries themes of power, triumph, and spiritual strength. In traditional contexts, this song is often associated with ceremonies where steady, driving mbira patterns help build momentum and connect players to ancestral energy.</p><p>Musically, Karigamombe is an intermediate-level song because it contains <strong>two complementary phrases</strong> that interlock to form the full cycle. Phrase A establishes the grounding rhythm, while Phrase B introduces movement and lift. The tempo is typically faster than beginner pieces like Nhemamusasa, and the right hand often plays more melodic movement, making coordination essential.</p><p>This lesson guides you through learning the structure, building accuracy, and developing confidence with both hands. Mastering Karigamombe strengthens your timing, tone control, and your ability to maintain clarity at higher tempos.</p><div class='info-box'><p class='info-box-title'>Practice Steps</p><ul><li>Listen to the full performance to understand the groove, tempo, and emotional character</li><li>Break the song into its two main phrases: <strong>Phrase A</strong> (foundation) and <strong>Phrase B</strong> (lift/movement)</li><li>Practice the Left Hand (bass) pattern for Phrase A slowly, focusing on steady pulse</li><li>Practice the Right Hand pattern for Phrase A, aiming for clean tone and correct finger placement</li><li>Combine both hands for Phrase A, maintaining clear interlocking</li><li>Repeat the same process for Phrase B</li><li>Play the full cycle (A → B) slowly, then gradually increase your speed while staying relaxed</li></ul></div>",
                    frame: '<iframe width="560" height="315" src="https://www.youtube.com/embed/_s5yLqeXzlw?si=Dp5HE4gNVYKeOxLB" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                    video: { 
                        title: "Karigamombe Full Performance & Lesson", 
                        type: "Complete tutorial", 
                        url: "https://youtu.be/bAqP3zzFC_4?si=ua-GpPKsZ6DQxoMZ" 
                    },
                    exercises: [
                        "I can play Phrase A cleanly",
                        "I can play Phrase B cleanly",
                        "I can transition from Phrase A to Phrase B without losing tempo",
                        "I can play the full Karigamombe cycle at slow tempo",
                        "I can play the full cycle at regular tempo with stable rhythm"
                    ],
                    quiz: [
                        { q: "What is the rhythmic feel of Karigamombe?", options: ["Steady with syncopation", "Simple 4/4 time", "Irregular meter"], correct: 0 },
                        { q: "How many main phrases make up one full cycle of Karigamombe?", options: ["One phrase", "Two phrases", "Three phrases"], correct: 1 },
                        { q: "What makes Karigamombe more advanced than Nhemamusasa?", options: ["Slower tempo", "Faster tempo and multi-phrase structure", "Fewer notes"], correct: 1 }
                    ]
                },
                {
                    id: "4.2",
                    title: "Endurance & Variation",
                    type: "practice",
                    content: "<p>Karigamombe is traditionally played for long periods during ceremonies, where maintaining energy, clarity, and emotional intensity is essential. This lesson focuses on <strong>endurance</strong>—your ability to play continuously without losing tone or rhythmic accuracy. It also introduces simple variations, which are a defining feature of advanced mbira playing.</p><p>Variations in Karigamombe are usually subtle, such as doubling a note, shifting a rhythmic accent, or adding a melodic turn in the right hand. These small changes give life to the music while keeping the core pattern recognizable. The goal is to make the song breathe without disrupting the dancers or other mbira players.</p>",
                    exercises: [
                        "Continuous Play: Perform full cycles of Karigamombe for 5–10 minutes without stopping",
                        "Tempo Control: Practice the song at slow, medium, and regular ceremonial tempo while keeping clarity",
                        "Introduce a small variation in the A-phrase (such as repeating a right-hand note)",
                        "Play one cycle with a variation, then return to the main pattern seamlessly",
                        "Record yourself to check if the variation fits smoothly within the rhythm"
                    ],
                    quiz: [
                        { q: "After 5 minutes of continuous play, what should you pay attention to?", options: ["Volume only", "Tempo, clarity, and hand coordination", "Only your right hand"], correct: 1 },
                        { q: "Why do mbira players add variations?", options: ["To show off", "To give life to the music and engage listeners", "To make the song confusing"], correct: 1 }
                    ]
                },
                {
                    id: "4.3",
                    title: "Hands-Together Coordination & Tone",
                    type: "practice",
                    content: "<p>Karigamombe requires moments where both hands strike simultaneously or nearly simultaneously, creating a powerful rhythmic drive. Achieving this cleanly is a hallmark of intermediate mbira playing. This lesson helps you build the precision and confidence needed for these interlocking moments.</p><p>Another essential skill is <strong>tone matching</strong>: ensuring that both hands produce notes with similar volume, brightness, and clarity. This prevents the bass (left hand) from overpowering the right hand and keeps the melody crisp. Good tone matching makes your playing sound unified, even at faster tempos.</p>",
                    exercises: [
                        "Use a metronome for 10 minutes to practice the interlocking moments of Phrase A and B",
                        "Focus on matching the brightness and attack of both hands with every strike",
                        "Practice at slow tempo, then gradually increase speed while staying relaxed",
                        "Simulate ensemble playing by imagining a second mbira player and keeping a steady supporting rhythm"
                    ],
                    quiz: [
                        { q: "What is 'tone matching'?", options: ["Playing louder", "Matching volume and brightness between both hands", "Playing faster"], correct: 1 },
                        { q: "Why practice with a metronome?", options: ["To make the song harder", "To maintain consistent timing and rhythm", "To avoid counting"], correct: 1 }
                    ]
                }
            ]
        },
        {
            id: "mod-5",
            title: "Module 5: Advanced Repertoire",
            description: "Classic songs and cultural context",
            lessons: [
                {
                    id: "5.1",
                    title: "Chemutengure",
                    type: "practice",
                    content: "<p><strong>Chemutengure</strong> is one of the most widely known Shona songs and is deeply rooted in everyday life, travel, and communal experiences. The name refers to the movement of a wagon or cart—chemute-ngure, the sound and motion of a wheel turning. Historically, the song is connected with travel during the colonial period, when long journeys were made on foot or by ox-drawn carts. People would sing Chemutengure to keep rhythm, pass time, and maintain a steady pace across long distances.</p><p>On mbira, Chemutengure is often taught early because it introduces important coordination skills and a strong rhythmic foundation. The song is built around a repeating pattern that can be played in two main styles:</p><ul><li><strong>Simultaneous Style:</strong> Both hands strike together or nearly together, creating a clear, unified pulse</li><li><strong>Alternating Style:</strong> The same notes are played by starting with the left hand and alternating with the right, giving the song a rolling or flowing feel</li></ul><div class='info-box'><p class='info-box-title'>Historical Context</p><p class='info-box-text'>Chemutengure is culturally significant because it reflects the lived experiences of Shona people during periods of forced travel, labor migration, and colonial movement. It is both a work song and a reflection of resilience. The melody is simple and cyclical, making it easy to sing along to, and traditionally it helped maintain morale and unity during long journeys.</p></div>",
                    video: { 
                        title: "Chemutengure Performance & History", 
                        type: "Cultural lesson (4:00 min)", 
                        url: "https://youtu.be/x4Wf2pmFqCA?si=uptxHdAAU5E7HPrr" 
                    },
                    frame: '<iframe width="560" height="315" src="https://www.youtube.com/embed/x4Wf2pmFqCA?si=s14XUyeCSWMxY9NM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                    exercises: [
                        "Practice Phrase One using the Simultaneous Style (both hands together)",
                        "Practice Phrase One using the Alternating Style (left hand start)",
                        "Repeat both styles for Phrase Two",
                        "Singing/Playing Integration: Hum the melody while playing alternating rhythm",
                        "Style Switch Challenge: Play five cycles in Simultaneous Style, then switch to Alternating Style"
                    ],
                    quiz: [
                        { q: "What is the historical purpose of Chemutengure?", options: ["War song", "Journey song to pass time during travel", "Harvest celebration"], correct: 1 },
                        { q: "How many notes are in each of the song's two phrases?", options: ["Four notes", "Six notes", "Eight notes"], correct: 1 },
                        { q: "What are the two main playing styles for Chemutengure?", options: ["Fast and slow", "Simultaneous and Alternating", "Loud and soft"], correct: 1 }
                    ]
                },
                {
                    id: "5.2",
                    title: "Chaminuka Ndimambo",
                    type: "practice",
                    content: "<p><strong>Chaminuka Ndimambo</strong> is a spiritual praise song dedicated to the legendary Shona spirit-medium and prophet, <strong>Chaminuka</strong>. Known as a guardian of the land and a figure of wisdom, Chaminuka is honored through music, especially in mbira ceremonies where the piece is played to call, praise, or acknowledge his presence.</p><p>This song carries a deep ceremonial weight. Its phrases are steady, spacious, and deliberately repetitive—reflecting the reverence associated with calling a powerful ancestral spirit. Unlike fast, driving songs such as Karigamombe, <strong>Chaminuka Ndimambo emphasizes calm authority, grounding, and clarity of tone</strong>.</p><p>Musically, the song uses a clear two-phrase structure, but each phrase feels open and expansive. Players must maintain a gentle but unwavering pulse. This makes the piece an excellent training ground for breath-like phrasing, steady tempo, and emotional expression on mbira.</p><div class='info-box'><p class='info-box-title'>Ceremonial Context</p><p class='info-box-text'>Chaminuka is one of the most respected mhondoro (lion spirits) in Shona tradition. When musicians play <em>Chaminuka Ndimambo</em> during bira ceremonies, the goal is to create an environment of respect, protection, and spiritual connection. The song is not played for entertainment—it carries purpose, identity, and ancestral authority.</p></div>",
                    video: { 
                        title: "Chaminuka Ndimambo – Performance & Meaning", 
                        type: "Cultural performance", 
                        url: "https://youtu.be/gfZiLSCoYrk?si=p_DIWBLqkGFjFT4V" 
                    },
                    frame: '<iframe width="560" height="315" src="https://www.youtube.com/embed/yZ8fcZo9M0M?si=c4Y66pGV7WaWJ0TE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
                    exercises: [
                        "Play Phrase One slowly, maintaining a calm and even pulse",
                        "Play Phrase Two and focus on expressive tone and steady finger placement",
                        "Combine both phrases at a relaxed tempo, without rushing transitions",
                        "Practice maintaining the same emotional feel for 3–5 minutes of continuous play",
                        "Hum or softly sing the vocal line while playing to internalize the phrasing"
                    ],
                    quiz: [
                        { q: "Who is Chaminuka in Shona tradition?", options: ["A warrior from the 1800s", "A mhondoro ancestor spirit and prophetic figure", "A mythical animal"], correct: 1 },
                        { q: "What is the emotional character of this song?", options: ["Fast and celebratory", "Calm, steady, and reverent", "Aggressive and loud"], correct: 1 },
                        { q: "How many main phrases make up Chaminuka Ndimambo?", options: ["One long phrase", "Two main phrases", "Four phrases"], correct: 1 }
                    ]
                },
                {
                    id: "5.3",
                    title: "Pfuvhu & Reparuzevha",
                    type: "practice",
                    content: "<p><strong>Pfuvhu</strong> and <strong>Reparuzevha</strong> (often taught together, or as Pfumvhu Yeruzevha) are classic pieces that broaden the advanced player's repertoire, exposing them to different stylistic approaches, speeds, and cyclical complexity.</p><p>These songs are excellent for developing musical flexibility and the ability to adapt to diverse rhythmic frameworks found across different mbira traditions. They may feature slight variations in tempo or more complex syncopated rhythms than your previous repertoire.</p><p>The goal here is to achieve the smooth, continuous play necessary for longer traditional performances. You must be able to '<strong>Play continuously</strong>' for extended periods, maintaining a stable, deep groove—a critical skill for traditional <em>bira</em> ceremonies that can last all night.</p>",
                    video: { title: "Pfumvhu Yeruzevha Continuous Play Lesson", type: "Advanced technique (5:00 min)", url: "https://youtu.be/7W2aj8vUBoE?si=QAblGRikd8kuW5ts" },
                    exercises: [
                        "Choose one version (Pfuvhu) and listen multiple times to identify rhythmic subdivisions",
                        "Focus on the duration of the cycle and repeat until effortless",
                        "Move to Reparuzevha and note the rhythmic differences",
                        "Endurance Challenge: Practice either song for 10-15 minutes continuously",
                        "Advanced Transition: Play five cycles of Pfuvhu, then immediately transition to Reparuzevha without stopping"
                    ],
                    quiz: [
                        { q: "How does the cyclical structure of Pfuvhu compare to Karigamombe?", options: ["More complex syncopation", "Same structure", "Simpler structure"], correct: 0 },
                        { q: "What is the purpose of the endurance challenge?", options: ["To tire you out", "To prepare for long ceremonial performances", "To test speed"], correct: 1 },
                        { q: "Why practice transitioning between Pfuvhu and Reparuzevha?", options: ["It's easier", "To develop adaptability and coordination", "To learn them faster"], correct: 1 }
                    ]
                }
            ]
        }
    ]
};

// State tracking
let state = {
    currentModule: 0,
    currentLesson: 0,
    completedLessons: [],
    quizScores: {}
};

let progressChartInstance = null;

// Initialize chart
function initChart() {
    const ctx = document.getElementById('progressChart').getContext('2d');
    progressChartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Remaining'],
            datasets: [{
                data: [0, 100],
                backgroundColor: ['#ea580c', '#e7e5e4'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '75%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        }
    });
}

// Update chart
function updateChart() {
    const totalLessons = courseData.modules.reduce(function(acc, m) { return acc + m.lessons.length; }, 0);
    const completedCount = state.completedLessons.length;
    const percentage = Math.round((completedCount / totalLessons) * 100);
    
    progressChartInstance.data.datasets[0].data = [completedCount, totalLessons - completedCount];
    progressChartInstance.update();

    document.getElementById('progress-text').innerText = percentage + '%';
}

// Render sidebar
function renderSidebar() {
    const container = document.getElementById('module-list');
    container.innerHTML = '';

    courseData.modules.forEach(function(mod, mIndex) {
        const modDiv = document.createElement('div');
        modDiv.className = 'module-block';
        
        const header = document.createElement('h4');
        header.className = 'module-header';
        header.textContent = mod.title;
        modDiv.appendChild(header);

        const ul = document.createElement('ul');
        ul.className = 'lessons-list';

        mod.lessons.forEach(function(lesson, lIndex) {
            const isCompleted = state.completedLessons.includes(lesson.id);
            const isActive = state.currentModule === mIndex && state.currentLesson === lIndex;
            
            const li = document.createElement('li');
            const button = document.createElement('button');
            button.className = 'lesson-button' + (isActive ? ' active' : '');
            button.onclick = function() { navigateTo(mIndex, lIndex); };
            
            const span = document.createElement('span');
            span.textContent = lesson.id + ' ' + lesson.title;
            button.appendChild(span);
            
            if (isCompleted) {
                const check = document.createElement('span');
                check.className = 'lesson-checkmark';
                check.textContent = 'done';
                button.appendChild(check);
            }
            
            li.appendChild(button);
            ul.appendChild(li);
        });

        modDiv.appendChild(ul);
        container.appendChild(modDiv);
    });
}

// Render lesson
function renderLessonContent() {
    const mod = courseData.modules[state.currentModule];
    const lesson = mod.lessons[state.currentLesson];
    const container = document.getElementById('lesson-container');

    document.getElementById('breadcrumbs').innerText = 'Home > ' + mod.title;
    document.getElementById('main-title').innerText = lesson.id + ': ' + lesson.title;

    let html = '';
    
    // Main content card
    html += '<div class="content-card"><h3 class="card-title">Lesson Overview</h3><div class="lesson-text">' + lesson.content + '</div></div>';

    // Video Frame card (if frame exists, show embedded video instead of just link)
    if (lesson.frame) {
        html += '<div class="video-card"><div class="video-header"><div class="video-icon">▶</div><div><h4 class="video-title">' + (lesson.video ? lesson.video.title : 'Video Lesson') + '</h4><p class="video-type">' + (lesson.video ? lesson.video.type : 'Tutorial') + '</p></div></div><div class="video-embed">' + lesson.frame + '</div></div>';
    } else if (lesson.video) {
        // Fallback: if no frame but video exists, show link
        html += '<div class="video-card"><div class="video-header"><div class="video-icon">▶</div><div><h4 class="video-title">' + lesson.video.title + '</h4><p class="video-type">' + lesson.video.type + '</p></div></div><div class="video-footer"><a href="' + lesson.video.url + '" target="_blank" class="video-link">Open Video →</a></div></div>';
    }

    // Reading resource card (if present)
    if (lesson.reading) {
        html += '<div class="video-card"><div class="video-header"><div class="video-icon"></div><div><h4 class="video-title">' + lesson.reading.title + '</h4><p class="video-type">Additional Reading</p></div></div><div class="video-footer"><a href="' + lesson.reading.url + '" target="_blank" class="video-link">Read More →</a></div></div>';
    }

    // Exercises card
    if (lesson.exercises) {
        html += '<div class="exercises-card"><h3 class="exercises-title">✦ Practical Exercises</h3><ul class="exercises-list">';
        lesson.exercises.forEach(function(ex) {
            html += '<li><span class="exercise-bullet">●</span><span class="exercise-text">' + ex + '</span></li>';
        });
        html += '</ul></div>';
    }

    // Quiz card
    if (lesson.quiz && lesson.quiz.length > 0) {
        const isPassed = state.quizScores[lesson.id] === lesson.quiz.length;
        html += '<div class="quiz-card"><h3 class="quiz-title">Knowledge Check</h3>';
        
        if (isPassed) {
            html += '<div class="quiz-passed">Quiz Passed! ✓</div>';
        } else {
            html += '<form class="quiz-form" onsubmit="submitQuiz(event, \'' + lesson.id + '\')">';
            lesson.quiz.forEach(function(q, idx) {
                html += '<div class="quiz-question"><p class="question-text">' + (idx + 1) + '. ' + q.q + '</p><div class="options-list">';
                q.options.forEach(function(opt, optIdx) {
                    html += '<label class="option-label"><input type="radio" name="q' + idx + '" value="' + optIdx + '" required><span class="option-text">' + opt + '</span></label>';
                });
                html += '</div></div>';
            });
            html += '<button type="submit" class="submit-btn">Submit Answers</button></form>';
        }
        html += '</div>';
    }

    // Completion button
    const completed = state.completedLessons.includes(lesson.id);
    html += '<div class="completion-section"><button onclick="markComplete(\'' + lesson.id + '\')" class="complete-btn ' + (completed ? 'completed' : 'not-completed') + '">' + (completed ? 'Lesson Completed ' : 'Mark as Complete') + '</button></div>';

    container.innerHTML = html;
}

// Navigation
function navigateTo(mIndex, lIndex) {
    state.currentModule = mIndex;
    state.currentLesson = lIndex;
    renderSidebar();
    renderLessonContent();
    updateNavButtons();
}

// Update nav buttons
function updateNavButtons() {
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    
    const isFirst = state.currentModule === 0 && state.currentLesson === 0;
    const lastMod = courseData.modules.length - 1;
    const lastLes = courseData.modules[lastMod].lessons.length - 1;
    const isLast = state.currentModule === lastMod && state.currentLesson === lastLes;

    prevBtn.disabled = isFirst;
    nextBtn.disabled = isLast;

    prevBtn.onclick = function() {
        if (state.currentLesson > 0) {
            navigateTo(state.currentModule, state.currentLesson - 1);
        } else if (state.currentModule > 0) {
            const prevMod = state.currentModule - 1;
            navigateTo(prevMod, courseData.modules[prevMod].lessons.length - 1);
        }
    };

    nextBtn.onclick = function() {
        const curModLen = courseData.modules[state.currentModule].lessons.length;
        if (state.currentLesson < curModLen - 1) {
            navigateTo(state.currentModule, state.currentLesson + 1);
        } else if (state.currentModule < courseData.modules.length - 1) {
            navigateTo(state.currentModule + 1, 0);
        }
    };
}

// Mark complete
function markComplete(lessonId) {
    if (!state.completedLessons.includes(lessonId)) {
        state.completedLessons.push(lessonId);
        updateChart();
        renderSidebar();
        renderLessonContent();
    }
}

// Submit quiz
function submitQuiz(e, lessonId) {
    e.preventDefault();
    const mod = courseData.modules[state.currentModule];
    const lesson = mod.lessons.find(function(l) { return l.id === lessonId; });
    
    let score = 0;
    lesson.quiz.forEach(function(q, idx) {
        const selected = document.querySelector('input[name="q' + idx + '"]:checked');
        if (selected && parseInt(selected.value) === q.correct) {
            score++;
        }
    });

    if (score === lesson.quiz.length) {
        state.quizScores[lessonId] = score;
        alert('Perfect! You got ' + score + '/' + lesson.quiz.length + '. Lesson marked as complete.');
        markComplete(lessonId);
    } else {
        alert('You got ' + score + '/' + lesson.quiz.length + '. Review and try again!');
    }
}

// Mobile menu
document.getElementById('mobile-menu-btn').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('menu-open');
});

// Initialize
window.onload = function() {
    initChart();
    renderSidebar();
    renderLessonContent();
    updateNavButtons();
    updateChart();
};
